<?php

namespace App\Services;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class TeamMemberService
{
    public function store(array $data, ?UploadedFile $avatarFile = null): TeamMember
    {
        $sortOrder = (int) TeamMember::max('sort_order') + 1;

        $attributes = $this->buildAttributes($data, $sortOrder);

        if (($attributes['avatar_type'] ?? '') === 'upload' && $avatarFile) {
            $attributes['avatar_path'] = $this->storeAvatar($avatarFile);
        }

        return TeamMember::create($attributes);
    }

    public function update(TeamMember $member, array $data, ?UploadedFile $avatarFile = null): TeamMember
    {
        $attributes = $this->buildAttributes($data, $member->sort_order, $member);

        if (($attributes['avatar_type'] ?? '') === 'upload') {
            if ($avatarFile) {
                $this->deleteAvatar($member);
                $attributes['avatar_path'] = $this->storeAvatar($avatarFile);
            } elseif ($member->avatar_type === 'upload') {
                $attributes['avatar_path'] = $member->avatar_path;
            }
        } else {
            $this->deleteAvatar($member);
            $attributes['avatar_path'] = null;
        }

        $member->update($attributes);

        return $member->fresh(['user']);
    }

    public function destroy(TeamMember $member): void
    {
        $this->deleteAvatar($member);
        $member->delete();
    }

    public function validatePayload(Request $request, bool $isUpdate = false, ?TeamMember $member = null): array
    {
        $sourceType = $request->input('source_type', $member?->user_id ? 'user' : 'manual');
        $avatarType = $request->input('avatar_type', 'icon');

        $rules = [
            'source_type' => ['required', Rule::in(['manual', 'user'])],
            'user_id' => [
                Rule::requiredIf($sourceType === 'user'),
                'nullable',
                'exists:users,id',
            ],
            'name' => [
                Rule::requiredIf($sourceType === 'manual'),
                'nullable',
                'string',
                'max:255',
            ],
            'role_title' => 'required|string|max:255',
            'bio' => 'nullable|string|max:5000',
            'avatar_type' => ['required', Rule::in(TeamMember::AVATAR_TYPES)],
            'avatar_icon' => [
                Rule::requiredIf($avatarType === 'icon'),
                'nullable',
                'string',
                'max:100',
            ],
            'accent_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'rating' => 'nullable|numeric|min:0|max:5',
            'courses_count' => 'nullable|integer|min:0|max:99999',
            'team_group' => ['required', Rule::in(TeamMember::TEAM_GROUPS)],
            'sort_order' => 'nullable|integer|min:0',
            'show_on_home' => 'sometimes|boolean',
            'show_on_page' => 'sometimes|boolean',
            'is_published' => 'sometimes|boolean',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => ['nullable', Rule::in(array_keys(TeamMember::SOCIAL_PLATFORMS))],
            'social_links.*.url' => 'nullable|url|max:500',
        ];

        if ($avatarType === 'upload' && ! $isUpdate) {
            $rules['avatar_file'] = 'required|image|max:2048';
        } elseif ($avatarType === 'upload') {
            $rules['avatar_file'] = 'nullable|image|max:2048';
        }

        if ($sourceType === 'user' && $avatarType === 'user') {
            $rules['avatar_file'] = 'nullable';
        }

        $validated = $request->validate($rules);

        $validated['user_id'] = $sourceType === 'user' ? ($validated['user_id'] ?? null) : null;
        $validated['name'] = $sourceType === 'manual' ? ($validated['name'] ?? null) : ($validated['name'] ?? null);
        $validated['source_type'] = $sourceType;
        $validated['is_published'] = $request->boolean('is_published', true);
        $validated['show_on_home'] = $request->boolean('show_on_home', true);
        $validated['show_on_page'] = $request->boolean('show_on_page', true);
        $validated['social_links'] = $this->normalizeSocialLinks($validated['social_links'] ?? []);

        return $validated;
    }

    /**
     * @return array<int, array{id: int, name: string, email: string, photo_url: ?string, roles: array<int, string>, courses_count: int}>
     */
    public function usersForPicker(?string $role = null, ?string $search = null): array
    {
        $query = User::query()
            ->where('is_active', true)
            ->with('roles')
            ->orderBy('name');

        if ($role && in_array($role, ['instructor', 'admin'], true)) {
            $query->role($role);
        } elseif ($role === 'staff') {
            $query->whereHas('roles', function ($q) {
                $q->whereIn('name', ['admin', 'instructor']);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->limit(50)->get()->map(function (User $user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'photo_url' => $user->photo ? asset('storage/' . $user->photo) : null,
                'roles' => $user->getRoleNames()->values()->all(),
                'courses_count' => \App\Models\Course::query()
                    ->where('instructor_id', $user->id)
                    ->where('status', 'published')
                    ->count(),
            ];
        })->values()->all();
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    public function roleFilterOptions(): array
    {
        return [
            ['value' => 'staff', 'label' => 'الفريق (مدربون + إداريون)'],
            ['value' => 'instructor', 'label' => 'المدربون فقط'],
            ['value' => 'admin', 'label' => 'الفريق الإداري فقط'],
        ];
    }

    private function buildAttributes(array $data, int $defaultSortOrder, ?TeamMember $existing = null): array
    {
        $avatarType = $data['avatar_type'];

        if ($data['source_type'] === 'user' && empty($data['name']) && ! empty($data['user_id'])) {
            $data['name'] = null;
        }

        return [
            'user_id' => $data['user_id'] ?? null,
            'name' => $data['name'] ?? null,
            'role_title' => $data['role_title'],
            'bio' => $data['bio'] ?? null,
            'avatar_type' => $avatarType,
            'avatar_icon' => $avatarType === 'icon' ? ($data['avatar_icon'] ?? 'fas fa-user') : null,
            'accent_color' => $data['accent_color'],
            'rating' => $data['rating'] ?? null,
            'courses_count' => $data['courses_count'] ?? null,
            'social_links' => $data['social_links'] ?? [],
            'team_group' => $data['team_group'],
            'show_on_home' => $data['show_on_home'] ?? true,
            'show_on_page' => $data['show_on_page'] ?? true,
            'is_published' => $data['is_published'] ?? true,
            'sort_order' => $data['sort_order'] ?? ($existing?->sort_order ?? $defaultSortOrder),
        ];
    }

    /**
     * @param  array<int, array{platform?: string, url?: string}>  $links
     * @return array<int, array{platform: string, url: string}>
     */
    private function normalizeSocialLinks(array $links): array
    {
        return collect($links)
            ->filter(fn ($link) => ! empty($link['platform']) && ! empty($link['url']))
            ->map(fn ($link) => [
                'platform' => $link['platform'],
                'url' => $link['url'],
            ])
            ->values()
            ->all();
    }

    private function storeAvatar(UploadedFile $file): string
    {
        return $file->store('team-members', 'public');
    }

    private function deleteAvatar(TeamMember $member): void
    {
        if ($member->avatar_path) {
            Storage::disk('public')->delete($member->avatar_path);
        }
    }
}

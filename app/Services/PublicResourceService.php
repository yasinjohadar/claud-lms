<?php

namespace App\Services;

use App\Models\PublicResource;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PublicResourceService
{
    public const MAX_FILE_SIZE_KB = 51200; // 50MB

    public function store(array $data, ?UploadedFile $file = null): PublicResource
    {
        $sortOrder = (int) PublicResource::max('sort_order') + 1;

        $attributes = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'],
            'sort_order' => $data['sort_order'] ?? $sortOrder,
            'is_published' => $data['is_published'] ?? true,
        ];

        if ($data['type'] === 'link') {
            $attributes['url'] = $data['url'];
        } else {
            $attributes = array_merge($attributes, $this->storeUploadedFile($file));
        }

        return PublicResource::create($attributes);
    }

    public function update(PublicResource $resource, array $data, ?UploadedFile $file = null): PublicResource
    {
        $attributes = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'],
            'sort_order' => $data['sort_order'] ?? $resource->sort_order,
            'is_published' => $data['is_published'] ?? $resource->is_published,
        ];

        if ($data['type'] === 'link') {
            $attributes['url'] = $data['url'];
            $this->deleteStoredFile($resource);
            $attributes['file_path'] = null;
            $attributes['file_original_name'] = null;
            $attributes['file_mime'] = null;
            $attributes['file_size'] = null;
        } else {
            $attributes['url'] = null;

            if ($file) {
                $this->deleteStoredFile($resource);
                $attributes = array_merge($attributes, $this->storeUploadedFile($file));
            }
        }

        $resource->update($attributes);

        return $resource->fresh();
    }

    public function destroy(PublicResource $resource): void
    {
        $this->deleteStoredFile($resource);
        $resource->delete();
    }

    public function validatePayload(Request $request, bool $isUpdate = false): array
    {
        $type = $request->input('type', 'link');

        $rules = [
            'title' => 'required|string|max:255',
            'type' => ['required', Rule::in(PublicResource::TYPES)],
            'description' => 'nullable|string|max:20000',
            'sort_order' => 'nullable|integer|min:0',
            'is_published' => 'nullable|boolean',
        ];

        if ($type === 'link') {
            $rules['url'] = 'required|url|max:2000';
        } else {
            $rules['file'] = ($isUpdate ? 'nullable' : 'required') . '|file|max:' . self::MAX_FILE_SIZE_KB;
        }

        $validated = $request->validate($rules);
        $validated['is_published'] = filter_var($validated['is_published'] ?? true, FILTER_VALIDATE_BOOLEAN);

        return $validated;
    }

    protected function storeUploadedFile(?UploadedFile $file): array
    {
        abort_unless($file, 422, 'الملف مطلوب');

        $path = $file->store('public-resources', 'public');

        return [
            'file_path' => $path,
            'file_original_name' => $file->getClientOriginalName(),
            'file_mime' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ];
    }

    protected function deleteStoredFile(PublicResource $resource): void
    {
        if ($resource->file_path && Storage::disk('public')->exists($resource->file_path)) {
            Storage::disk('public')->delete($resource->file_path);
        }
    }
}

<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseResource;
use App\Models\CourseSection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CourseResourceService
{
    public const MAX_FILE_SIZE_KB = 51200; // 50MB

    public function store(Course $course, array $data, ?UploadedFile $file = null): CourseResource
    {
        $sectionId = $data['course_section_id'] ?? null;

        if ($sectionId) {
            CourseSection::query()
                ->where('course_id', $course->id)
                ->where('id', $sectionId)
                ->firstOrFail();
        }

        $sortOrder = $this->nextSortOrder($course, $sectionId);

        $attributes = [
            'course_id' => $course->id,
            'course_section_id' => $sectionId,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'],
            'sort_order' => $sortOrder,
            'is_published' => $data['is_published'] ?? true,
        ];

        if ($data['type'] === 'link') {
            $attributes['url'] = $data['url'];
        } else {
            $attributes = array_merge($attributes, $this->storeUploadedFile($course, $file));
        }

        return CourseResource::create($attributes);
    }

    public function update(CourseResource $resource, array $data, ?UploadedFile $file = null): CourseResource
    {
        $course = $resource->course;
        $sectionId = array_key_exists('course_section_id', $data)
            ? $data['course_section_id']
            : $resource->course_section_id;

        if ($sectionId) {
            CourseSection::query()
                ->where('course_id', $course->id)
                ->where('id', $sectionId)
                ->firstOrFail();
        }

        $attributes = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'],
            'course_section_id' => $sectionId,
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
                $attributes = array_merge($attributes, $this->storeUploadedFile($course, $file));
            }
        }

        $resource->update($attributes);

        return $resource->fresh();
    }

    public function destroy(CourseResource $resource): void
    {
        $this->deleteStoredFile($resource);
        $resource->delete();
    }

    public function reorderResources(Course $course, ?int $sectionId, array $resourceIds): void
    {
        DB::transaction(function () use ($course, $sectionId, $resourceIds) {
            foreach ($resourceIds as $index => $resourceId) {
                CourseResource::query()
                    ->where('course_id', $course->id)
                    ->where('id', $resourceId)
                    ->when(
                        $sectionId,
                        fn ($q) => $q->where('course_section_id', $sectionId),
                        fn ($q) => $q->whereNull('course_section_id')
                    )
                    ->update(['sort_order' => $index + 1]);
            }
        });
    }

    public function validatePayload(Request $request, bool $isUpdate = false): array
    {
        $type = $request->input('type', 'link');

        $rules = [
            'title' => 'required|string|max:255',
            'type' => ['required', Rule::in(CourseResource::TYPES)],
            'description' => 'nullable|string|max:10000',
            'course_section_id' => 'nullable|integer|exists:course_sections,id',
            'is_published' => 'nullable|boolean',
        ];

        if ($type === 'link') {
            $rules['url'] = 'required|url|max:2000';
        } else {
            $rules['file'] = ($isUpdate ? 'nullable' : 'required') . '|file|max:' . self::MAX_FILE_SIZE_KB;
        }

        return $request->validate($rules);
    }

    protected function nextSortOrder(Course $course, ?int $sectionId): int
    {
        $query = CourseResource::query()->where('course_id', $course->id);

        if ($sectionId) {
            $query->where('course_section_id', $sectionId);
        } else {
            $query->whereNull('course_section_id');
        }

        return (int) $query->max('sort_order') + 1;
    }

    protected function storeUploadedFile(Course $course, ?UploadedFile $file): array
    {
        abort_unless($file, 422, 'الملف مطلوب');

        $path = $file->store('courses/resources/' . $course->id, 'public');

        return [
            'file_path' => $path,
            'file_original_name' => $file->getClientOriginalName(),
            'file_mime' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ];
    }

    protected function deleteStoredFile(CourseResource $resource): void
    {
        if ($resource->file_path && Storage::disk('public')->exists($resource->file_path)) {
            Storage::disk('public')->delete($resource->file_path);
        }
    }
}

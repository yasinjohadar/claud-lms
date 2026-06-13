<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CourseCatalogService
{
    public function buildQuery(array $filters = []): Builder
    {
        $query = Course::query()
            ->published()
            ->with(['category', 'instructor', 'tags']);

        if (! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (! empty($filters['categories'])) {
            $slugs = is_array($filters['categories']) ? $filters['categories'] : [$filters['categories']];
            $query->whereHas('category', fn ($q) => $q->whereIn('slug', $slugs));
        }

        if (! empty($filters['tags'])) {
            $slugs = is_array($filters['tags']) ? $filters['tags'] : [$filters['tags']];
            $query->whereHas('tags', fn ($q) => $q->whereIn('slug', $slugs));
        }

        if (! empty($filters['levels'])) {
            $levels = is_array($filters['levels']) ? $filters['levels'] : [$filters['levels']];
            $query->whereIn('level', $levels);
        }

        if (isset($filters['price_max']) && $filters['price_max'] !== '' && $filters['price_max'] !== null) {
            $query->where('price', '<=', (float) $filters['price_max']);
        }

        $this->applySort($query, $filters['sort'] ?? 'popular');

        return $query;
    }

    public function paginate(array $filters = [], int $perPage = 9): LengthAwarePaginator
    {
        return $this->buildQuery($filters)->paginate($perPage)->withQueryString();
    }

    public function limit(array $filters = [], int $limit = 5): Collection
    {
        return $this->buildQuery($filters)->limit($limit)->get();
    }

    public function filtersFromRequest(Request $request): array
    {
        return [
            'search' => $request->input('search'),
            'categories' => $request->input('categories', $request->input('category') ? [$request->input('category')] : []),
            'tags' => $request->input('tags', []),
            'levels' => $request->input('levels', []),
            'price_max' => $request->input('price_max'),
            'sort' => $request->input('sort', 'popular'),
        ];
    }

    public function getMaxPrice(): float
    {
        return (float) (Course::published()->max('price') ?: 250);
    }

    protected function applySort(Builder $query, string $sort): void
    {
        match ($sort) {
            'newest' => $query->orderByDesc('published_at')->orderByDesc('created_at'),
            'price-asc' => $query->orderBy('price'),
            'price-desc' => $query->orderByDesc('price'),
            'rating' => $query->orderByDesc('rating_avg')->orderByDesc('students_count'),
            default => $query->orderByDesc('students_count')->orderByDesc('rating_avg'),
        };
    }
}

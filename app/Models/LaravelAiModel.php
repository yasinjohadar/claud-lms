<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LaravelAiModel extends Model
{
    protected $fillable = [
        'name',
        'provider',
        'model_id',
        'capabilities',
        'max_tokens',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'capabilities' => 'array',
        'is_active' => 'boolean',
        'max_tokens' => 'integer',
        'sort_order' => 'integer',
    ];

    public function scopeActiveOrdered(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    public function scopeForCapability(Builder $query, string $capability): Builder
    {
        return $query->whereJsonContains('capabilities', $capability);
    }
}

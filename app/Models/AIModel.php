<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIModel extends Model
{
    protected $table = 'ai_models';

    protected $fillable = [
        'name',
        'provider',
        'model_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}

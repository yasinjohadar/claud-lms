<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionParticipant extends Model
{
    protected $fillable = [
        'competition_id',
        'user_id',
        'current_value',
        'rank',
        'joined_at',
    ];

    protected $casts = [
        'current_value' => 'integer',
        'rank' => 'integer',
        'joined_at' => 'datetime',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

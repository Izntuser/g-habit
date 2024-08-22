<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HabitLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'habit_id',
        'date',
        'time',
        'claimed_reward',
        'unit_progress',
        'goal',
        'skipped',
        'notes',
        'created_at',
        'updated_at'
    ];
    protected $table = 'habit_logs';
    protected $appends = ['is_completed'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function habit(): BelongsTo
    {
        return $this->belongsTo(UserHabit::class);
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->unit_progress >= $this->goal;
    }
}

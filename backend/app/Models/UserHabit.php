<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHabit extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'description',
        'user_unit_id',
        'default_unit_id',
        'week_days_bitmask',
        'goal',
        'reward',
        'due_to',
        'archived',
        'created_at',
        'updated_at'
    ];
    protected $table = 'user_habits';
    protected $appends = ['week_days'];

    const TYPE_BUILDING = 1;
    const TYPE_QUITTING = 2;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getWeekDaysAttribute(): array
    {
        return get_days_from_bitmask($this->week_days_bitmask);
    }
}

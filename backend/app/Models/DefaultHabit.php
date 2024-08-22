<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultHabit extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'description',
        'default_unit_id',
        'week_days_bitmask',
        'goal',
        'reward'
    ];
    protected $table = 'default_habits';
    public $timestamps = false;
    protected $appends = ['week_days'];

    const TYPE_BUILDING = 1;
    const TYPE_QUITTING = 2;

    public function getWeekDaysAttribute(): array
    {
        return get_days_from_bitmask($this->week_days_bitmask);
    }
}

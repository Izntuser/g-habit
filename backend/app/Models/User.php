<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'timezone',
        'rewa_coins',
        'subscription_end',
        'avatar',
        'lang',
        'on_validation',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function habits(): HasMany
    {
        return $this->hasMany(UserHabit::class);
    }

    public function habitLogs(): HasMany
    {
        return $this->hasMany(HabitLog::class);
    }

    public function todayHabitLog($habitId): HasMany
    {
        $currentDateTime = Carbon::now($this->timezone);
        $date = $currentDateTime->format('Y-m-d');
        return $this->habitLogs()
            ->where('habit_id', $habitId)
            ->where('date', $date);
    }

    public function yesterdayHabitLogs(): HasMany
    {
        $currentDateTime = Carbon::now($this->timezone);
        $yesterdayDate = $currentDateTime->subDay()->format('Y-m-d');
        return $this->habitLogs()
            ->where('date', $yesterdayDate);
    }

    public function yesterdayHabits(): HasMany
    {
        $currentDateTime = Carbon::now($this->timezone);
        $yesterdayWeekDay = $currentDateTime->subDay()->format('l');
        $yesterdayWeekDayBitmask = get_bitmask_from_days([$yesterdayWeekDay]);
        return $this->habits()
            ->where('week_days_bitmask', '&', $yesterdayWeekDayBitmask);
    }
}

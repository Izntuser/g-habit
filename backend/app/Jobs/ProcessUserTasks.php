<?php

namespace App\Jobs;

use AllowDynamicProperties;
use App\Models\HabitLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessUserTasks implements ShouldQueue
{
    use Queueable;


    public User $user;

    /**
     * Create a new job instance.
     *
     * @param User $data
     */
    public function __construct(User $data)
    {
        $this->user = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('ProcessUserTasks. started');

        $userHabits = $this->user->yesterdayHabits;
        $userLoggedHabits = $this->user->yesterdayHabitLogs;

        $habitLogsToInsert = [];
        foreach ($userHabits as $habit) {
            if ($userLoggedHabits->contains('habit_id', $habit->id)) {
                continue;
            }

            $now = Carbon::now();
            $yesterdayDate =  Carbon::now($habit->user->timezone)->subDay()->format('Y-m-d');

            $habitLogsToInsert[] = [
                'user_id' => $habit->user_id,
                'habit_id' => $habit->id,
                'unit_progress' => 0,
                'goal' => $habit->goal,
                'date' => $yesterdayDate,
                'time' => null,
                'claimed_reward' => 0,
                'notes' => null,
                'skipped' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (count($habitLogsToInsert) > 0) {
            HabitLog::insert($habitLogsToInsert);
        }
    }
}

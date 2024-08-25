<?php

namespace App\Console\Commands;

use App\Jobs\ProcessUserTasks;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckUserTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-user-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store in habit_logs user tasks if they were not created.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info('CheckUserTasks started');
        $now = Carbon::now('UTC');

        User::chunk(200, function ($users) use ($now) {
            foreach ($users as $user) {
                $localTime = $now->copy()->setTimezone($user->timezone);
                $localMidnight = $now->copy()->setTimezone($user->timezone)->startOfDay();

                $diffInMinutes = $localTime->diffInMinutes($localMidnight);

                if ($diffInMinutes >= 0 && $diffInMinutes < 5) {
                    ProcessUserTasks::dispatch($user);
                }
            }
        });
    }

}

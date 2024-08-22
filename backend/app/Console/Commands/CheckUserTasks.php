<?php

namespace App\Console\Commands;

use App\Jobs\ProcessUserTasks;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
    protected $description = 'Logs user tasks.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $now = Carbon::now('UTC');

        User::chunk(200, function ($users) use ($now) {
            foreach ($users as $user) {
                $localTime = $now->copy()->setTimezone($user->time_zone);

                if ($localTime->format('H:i') == '00:00') {
                    ProcessUserTasks::dispatch($user);
                }
            }
        });
    }

}

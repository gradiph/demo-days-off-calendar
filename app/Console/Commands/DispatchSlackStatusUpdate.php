<?php

namespace App\Console\Commands;

use App\Jobs\UpdateSlackStatus;
use App\Models\User;
use App\Models\UserOffDay;
use Illuminate\Console\Command;

class DispatchSlackStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slack:status-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch Slack day-off status jobs at 2AM in user timezone';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nowUtc = now();

        User::whereNotNull('timezone')
            ->whereNotNull('slack_token')
            ->each(function ($user) use ($nowUtc) {
                $userTime = $nowUtc->copy()->setTimezone($user->timezone);
                
                if ($userTime->format('H:i') === '02:00') {
                    $userDayOff = UserOffDay::where('user_id', $user->id)
                        ->whereDate('date', $userTime->format('Y-m-d'))
                        ->first();
                    if ($userDayOff) {
                        UpdateSlackStatus::dispatch($user, $userDayOff);
                        $this->info("Dispatched SlackStatusUpdate for user {$user->id} at {$userTime}");
                    }
                }
            });
    }
}

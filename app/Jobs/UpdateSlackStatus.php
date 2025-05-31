<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserOffDay;
use App\Services\SlackService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateSlackStatus implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected User $user, protected UserOffDay $userOffDay)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $slackService = new SlackService($this->user);
        
        $isSuccessful = $slackService->setStatus('Day off : ' . $this->userOffDay->reason);
        if ($isSuccessful && $this->user->is_away_on_off_day) {
            $isSuccessful = $slackService->setPresence('away');
        }
        if (!$isSuccessful) {
            $this->user->slack_token = null;
            $this->user->save();
        }
    }
}

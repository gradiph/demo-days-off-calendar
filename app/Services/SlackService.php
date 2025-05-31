<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class SlackService
{
    public function __construct(protected User $user)
    {

    }

    public function setStatus(string $statusText, string $emoji = ':palm_tree:'): bool
    {
        $userTime = now()
            ->setTimezone($this->user->timezone)
            ->setTime(17, 0);
        $expiration = $userTime->timestamp;
        $profile = [
            'status_text' => $statusText,
            'status_emoji' => $emoji,
            'status_expiration' => $expiration,
        ];

        $response = Http::withToken($this->user->slack_token)
            ->post('https://slack.com/api/users.profile.set', [
                'profile' => $profile,
            ]);

        return $response->successful();
    }

    public function setPresence(string $presence = 'auto'): bool
    {
        $response = Http::withToken($this->user->slack_token)
            ->post('https://slack.com/api/users.setPresence', [
                'presence' => $presence,
            ]);

        return $response->successful();
    }
}

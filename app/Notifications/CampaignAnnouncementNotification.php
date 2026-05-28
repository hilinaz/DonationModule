<?php

namespace App\Notifications;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CampaignAnnouncementNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly Campaign $campaign) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'campaign_announcement',
            'campaign_id' => $this->campaign->id,
            'title' => 'New campaign: ' . $this->campaign->name,
            'message' => 'A new fundraising campaign is open for donations.',
            'action_url' => route('donor.portal'),
            'goal_amount' => $this->campaign->goal_amount,
            'goal_currency' => $this->campaign->goal_currency,
        ];
    }
}

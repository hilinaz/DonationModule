<?php

namespace App\Notifications;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DonationThankYouNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly Donation $donation) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $campaignName = $this->donation->campaign?->name ?? 'General Fund';

        return [
            'kind' => 'donation_thank_you',
            'donation_id' => $this->donation->id,
            'campaign_id' => $this->donation->campaign_id,
            'title' => 'Thank you for your donation',
            'message' => 'Thank you for supporting ' . $campaignName . '. Your generosity makes a real difference.',
            'action_url' => route('donor.receipt', $this->donation),
            'amount' => $this->donation->amount_original,
            'currency' => $this->donation->currency,
        ];
    }
}

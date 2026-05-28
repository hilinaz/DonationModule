<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\CommunicationLog;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\User;
use App\Notifications\CampaignAnnouncementNotification;
use App\Notifications\DonationThankYouNotification;

class InAppNotificationService
{
    public function sendDonationThankYou(Donation $donation): void
    {
        $donation->loadMissing(['donor', 'campaign']);

        $donor = $donation->donor;
        $user = $this->userForDonor($donor);

        if (! $donor || ! $user || $this->hasDonationThankYouBeenSent($donation)) {
            return;
        }

        $user->notify(new DonationThankYouNotification($donation));

        $this->log($donor, $donation->campaign, 'thank_you', 'sent', [
            'donation_id' => $donation->id,
            'user_id' => $user->id,
        ]);
    }

    public function announceCampaign(Campaign $campaign): void
    {
        if ($campaign->status !== 'active' || ! $campaign->is_public) {
            return;
        }

        Donor::query()
            ->whereNotNull('email')
            ->chunkById(100, function ($donors) use ($campaign): void {
                foreach ($donors as $donor) {
                    $user = $this->userForDonor($donor);

                    if (! $user || $this->hasCampaignAnnouncementBeenSent($campaign, $donor)) {
                        continue;
                    }

                    $user->notify(new CampaignAnnouncementNotification($campaign));

                    $this->log($donor, $campaign, 'campaign_announcement', 'sent', [
                        'campaign_id' => $campaign->id,
                        'user_id' => $user->id,
                    ]);
                }
            });
    }

    private function userForDonor(?Donor $donor): ?User
    {
        if (! $donor || blank($donor->email)) {
            return null;
        }

        return User::where('email', $donor->email)->first();
    }

    private function hasDonationThankYouBeenSent(Donation $donation): bool
    {
        return CommunicationLog::query()
            ->where('message_type', 'thank_you')
            ->where('status', 'sent')
            ->where('meta->donation_id', $donation->id)
            ->exists();
    }

    private function hasCampaignAnnouncementBeenSent(Campaign $campaign, Donor $donor): bool
    {
        return CommunicationLog::query()
            ->where('donor_id', $donor->id)
            ->where('campaign_id', $campaign->id)
            ->where('message_type', 'campaign_announcement')
            ->where('status', 'sent')
            ->exists();
    }

    private function log(Donor $donor, ?Campaign $campaign, string $messageType, string $status, array $meta = []): void
    {
        CommunicationLog::create([
            'donor_id' => $donor->id,
            'campaign_id' => $campaign?->id,
            'channel' => 'in_app',
            'message_type' => $messageType,
            'subject' => null,
            'content' => null,
            'status' => $status,
            'sent_at' => $status === 'sent' ? now() : null,
            'meta' => $meta,
        ]);
    }
}

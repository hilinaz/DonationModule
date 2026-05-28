<?php

namespace App\Services;

use App\Models\Donor;
use Illuminate\Support\Carbon;

class DonorLifecycleService
{
    public function refresh(Donor $donor): Donor
    {
        $lastDonationDate = $donor->donations()->max('donated_at');

        if (! $lastDonationDate) {
            $donor->lifecycle_stage = 'new';
        } elseif (Carbon::parse($lastDonationDate)->lt(now()->subMonths(12))) {
            $donor->lifecycle_stage = 'inactive';
        } else {
            $donor->lifecycle_stage = 'active';
        }

        $donor->last_engaged_at = $lastDonationDate ? Carbon::parse($lastDonationDate) : null;
        $donor->engagement_score = $this->score($donor);
        $donor->is_active = $donor->lifecycle_stage !== 'inactive';
        $donor->save();

        return $donor;
    }

    private function score(Donor $donor): int
    {
        $recentDonationCount = $donor->donations()
            ->where('donated_at', '>=', now()->subMonths(12))
            ->count();

        $recentDonationAmount = (float) $donor->donations()
            ->where('donated_at', '>=', now()->subMonths(12))
            ->sum('amount_base');

        return min(100, (int) (($recentDonationCount * 10) + floor($recentDonationAmount / 100)));
    }
}

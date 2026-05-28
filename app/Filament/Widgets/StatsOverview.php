<?php

namespace App\Filament\Widgets;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donor;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalRaised   = Donation::where('payment_status', 'completed')->sum('amount_base');
        $totalDonors   = Donor::count();
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $pendingDonations = Donation::where('payment_status', 'pending')->count();

        return [
            Stat::make('Total Raised', '$' . number_format($totalRaised, 2))
                ->description('Completed donations (USD)')
                ->color('success'),

            Stat::make('Total Donors', number_format($totalDonors))
                ->description('Registered donor profiles')
                ->color('info'),

            Stat::make('Active Campaigns', $activeCampaigns)
                ->description('Currently accepting donations')
                ->color('warning'),

            Stat::make('Pending Donations', $pendingDonations)
                ->description('Awaiting validation')
                ->color('danger'),
        ];
    }
}

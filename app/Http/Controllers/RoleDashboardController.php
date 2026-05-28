<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class RoleDashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        if ($user->hasRole('Fundraising Manager')) {
            $campaignCount = \App\Models\Campaign::count();
            $activeCampaignCount = \App\Models\Campaign::where('status', 'active')->count();
            $totalGoalAmount = \App\Models\Campaign::sum('goal_amount');
            $totalRaisedAmount = \App\Models\Donation::where('payment_status', 'completed')->sum('amount_base');
            $recentCampaigns = \App\Models\Campaign::latest()->limit(5)->get();
            $recentDonors = \App\Models\Donor::latest()->limit(5)->get();

            return view('dashboards.fundraising-manager', compact(
                'campaignCount',
                'activeCampaignCount',
                'totalGoalAmount',
                'totalRaisedAmount',
                'recentCampaigns',
                'recentDonors'
            ));
        }

        $roleViewMap = [
            'Admin' => 'dashboards.admin',
            'Finance' => 'dashboards.finance',
            'Marketing' => 'dashboards.marketing',
            'Donor' => 'dashboards.donor',
            'Auditor' => 'dashboards.auditor',
        ];

        foreach ($roleViewMap as $role => $view) {
            if ($user->hasRole($role)) {
                return view($view);
            }
        }

        return view('dashboard');
    }
}

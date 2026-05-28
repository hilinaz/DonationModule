<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RoleDashboardController extends Controller
{
    public function __invoke(): View|RedirectResponse
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
            'Auditor' => 'dashboards.auditor',
        ];

        foreach ($roleViewMap as $role => $view) {
            if ($user->hasRole($role)) {
                return view($view);
            }
        }

        // Donors get redirected to their dedicated portal with data
        if ($user->hasRole('Donor')) {
            return redirect()->route('donor.portal');
        }

        return view('dashboard');
    }
}

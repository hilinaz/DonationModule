<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonorPortalController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Find the donor record linked to this user's email
        $donor = Donor::where('email', $user->email)->first();

        // Active campaigns with raised amounts
        $activeCampaigns = Campaign::where('status', 'active')
            ->orderBy('ends_at')
            ->get()
            ->map(function (Campaign $campaign) {
                $raised = $campaign->donations()->where('payment_status', 'completed')->sum('amount_base');
                $campaign->raised = $raised;
                $campaign->pct = $campaign->goal_amount > 0
                    ? min(100, round(($raised / $campaign->goal_amount) * 100, 1))
                    : 0;
                return $campaign;
            });

        // Donation history for this donor
        $myDonations = $donor
            ? Donation::with('campaign')
                ->where('donor_id', $donor->id)
                ->latest('donated_at')
                ->get()
            : collect();

        $totalGiven = $myDonations->where('payment_status', 'completed')->sum('amount_base');

        return view('dashboards.donor', compact(
            'donor',
            'activeCampaigns',
            'myDonations',
            'totalGiven',
        ));
    }

    public function donate(Request $request, Campaign $campaign): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:999999'],
        ]);

        $user = auth()->user();
        $donor = Donor::where('email', $user->email)->first();

        if (! $donor) {
            return back()->with('error', 'No donor profile found for your account. Please contact an administrator.');
        }

        Donation::create([
            'donor_id'            => $donor->id,
            'campaign_id'         => $campaign->id,
            'donation_type'       => 'one_time',
            'source'              => 'portal',
            'payment_status'      => 'completed',
            'currency'            => 'USD',
            'amount_original'     => $request->amount,
            'exchange_rate'       => 1,
            'amount_base'         => $request->amount,
            'base_currency'       => 'USD',
            'gift_aid_eligible'   => false,
            'donated_at'          => now(),
        ]);

        return back()->with('success', 'Thank you! Your donation of $' . number_format($request->amount, 2) . ' to "' . $campaign->name . '" has been recorded.');
    }
}

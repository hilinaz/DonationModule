<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donor;
use App\Services\DonorLifecycleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonorPortalController extends Controller
{
    // Exchange rates relative to USD (static approximations — replace with live API if needed)
    private const EXCHANGE_RATES = [
        'USD' => 1.0,
        'GBP' => 0.79,
        'EUR' => 0.92,
        'ETB' => 57.50,
        'CAD' => 1.36,
        'AUD' => 1.53,
    ];

    public function index(): View
    {
        $user  = auth()->user();
        $donor = Donor::where('email', $user->email)->first();

        $activeCampaigns = Campaign::where('status', 'active')
            ->orderBy('ends_at')
            ->get()
            ->map(function (Campaign $campaign) {
                $raised            = (float) $campaign->donations()->where('payment_status', 'completed')->sum('amount_base');
                $campaign->raised  = $raised;
                $campaign->pct     = $campaign->goal_amount > 0
                    ? min(100, round(($raised / $campaign->goal_amount) * 100, 1))
                    : 0;
                return $campaign;
            });

        $myDonations = $donor
            ? Donation::with('campaign')
                ->where('donor_id', $donor->id)
                ->latest('donated_at')
                ->get()
            : collect();

        $totalGiven = (float) $myDonations->where('payment_status', 'completed')->sum('amount_base');

        $currencies = array_keys(self::EXCHANGE_RATES);

        return view('dashboards.donor', compact(
            'donor',
            'activeCampaigns',
            'myDonations',
            'totalGiven',
            'currencies',
        ));
    }

    public function donate(Request $request, Campaign $campaign): RedirectResponse
    {
        $validated = $request->validate([
            'donation_type' => ['required', 'in:one_time,recurring,pledge,in_kind'],
            'currency'      => ['required_unless:donation_type,in_kind', 'in:' . implode(',', array_keys(self::EXCHANGE_RATES))],
            'amount'        => ['required_unless:donation_type,in_kind', 'nullable', 'numeric', 'min:0.01', 'max:9999999'],
            'gift_aid'      => ['nullable', 'boolean'],
            'notes'         => ['nullable', 'string', 'max:500'],
        ]);

        $user  = auth()->user();
        $donor = Donor::where('email', $user->email)->first();

        if (! $donor) {
            return back()->with('error', 'No donor profile is linked to your account. Please contact an administrator.');
        }

        if ($campaign->status !== 'active') {
            return back()->with('error', 'This campaign is no longer accepting donations.');
        }

        $currency     = $validated['currency'];
        $rate         = self::EXCHANGE_RATES[$currency] ?? 1.0;
        $amountOrig   = (float) $validated['amount'];
        $amountBase   = round($amountOrig / $rate, 2); // convert to USD base

        $donation = Donation::create([
            'donor_id'              => $donor->id,
            'campaign_id'           => $campaign->id,
            'donation_type'         => $validated['donation_type'],
            'source'                => 'portal',
            'payment_status'        => 'completed',
            'currency'              => $currency,
            'amount_original'       => $amountOrig,
            'exchange_rate'         => $rate,
            'amount_base'           => $amountBase,
            'base_currency'         => 'USD',
            'gift_aid_eligible'     => $request->boolean('gift_aid'),
            'donated_at'            => now(),
            'meta'                  => $validated['notes'] ? ['notes' => $validated['notes']] : null,
        ]);

        app(DonorLifecycleService::class)->refresh($donor);

        // Redirect to receipt page
        return redirect()->route('donor.receipt', $donation)
            ->with('success', 'Thank you! Your donation has been recorded.');
    }

    public function receipt(Donation $donation): View
    {
        // Ensure donor can only view their own receipts
        $user  = auth()->user();
        $donor = Donor::where('email', $user->email)->first();

        abort_if(! $donor || $donation->donor_id !== $donor->id, 403);

        $donation->load(['donor', 'campaign']);

        return view('donations.receipt', compact('donation'));
    }
}

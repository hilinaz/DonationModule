<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donor;
use App\Services\DonationService;
use App\Services\DonorLifecycleService;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function __construct(
        private DonationService $donationService,
        private DonorLifecycleService $lifecycleService,
    ) {}

    public function index(Request $request)
    {
        $query = Donation::with(['donor', 'campaign'])->orderByDesc('donated_at');

        if ($request->filled('donor_id')) {
            $query->where('donor_id', $request->donor_id);
        }
        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('donation_type')) {
            $query->where('donation_type', $request->donation_type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('donated_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('donated_at', '<=', $request->date_to);
        }

        $donations = $query->paginate(20)->withQueryString();
        $campaigns = Campaign::orderBy('name')->get();
        $donors    = Donor::orderBy('first_name')->get();

        return view('donations.index', compact('donations', 'campaigns', 'donors'));
    }

    public function create(Request $request)
    {
        $campaigns    = Campaign::orderBy('name')->get();
        $donors       = Donor::orderBy('first_name')->get();
        $selectedDonor = $request->filled('donor_id') ? Donor::find($request->donor_id) : null;

        return view('donations.create', compact('campaigns', 'donors', 'selectedDonor'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_id'              => ['required', 'exists:donors,id'],
            'campaign_id'           => ['nullable', 'exists:campaigns,id'],
            'donation_type'         => ['required', 'in:one_time,recurring,pledge,in_kind'],
            'source'                => ['required', 'in:portal,event,offline'],
            'currency'              => ['required', 'in:USD,GBP,EUR,ETB'],
            'amount_original'       => ['required', 'numeric', 'min:0.01'],
            'exchange_rate'         => ['nullable', 'numeric', 'min:0'],
            'payment_status'        => ['required', 'in:pending,completed,failed,refunded'],
            'gift_aid_eligible'     => ['nullable', 'boolean'],
            'donated_at'            => ['required', 'date'],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['gift_aid_eligible'] = $request->boolean('gift_aid_eligible');
        $validated['exchange_rate']     = $validated['exchange_rate'] ?? 1;

        $donation = $this->donationService->create($validated);

        $donor = Donor::find($validated['donor_id']);
        $this->lifecycleService->refresh($donor);

        return redirect()->route('donations.show', $donation)
            ->with('status', 'Donation recorded successfully.');
    }

    public function show(Donation $donation)
    {
        $donation->load(['donor', 'campaign']);

        return view('donations.show', compact('donation'));
    }

    public function receipt(Donation $donation)
    {
        $donation->load(['donor', 'campaign']);

        return view('donations.receipt', compact('donation'));
    }

    public function validateDonation(Request $request, Donation $donation)
    {
        $donation->update(['payment_status' => 'completed']);

        if ($donation->donor) {
            $this->lifecycleService->refresh($donation->donor);
        }

        return redirect()->back()->with('status', 'Donation validated successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CampaignController extends Controller
{
    /**
     * Display a listing of the campaigns.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();

        $campaigns = Campaign::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($q) use ($search): void {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->withCount('donations')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('campaigns.index', compact('campaigns', 'search', 'status'));
    }

    /**
     * Show the form for creating a new campaign.
     */
    public function create(): View
    {
        return view('campaigns.create');
    }

    /**
     * Store a newly created campaign in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCampaign($request);

        $campaign = Campaign::create($validated);

        return redirect()->route('campaigns.show', $campaign)
            ->with('status', 'Campaign created successfully.');
    }

    /**
     * Display the specified campaign.
     */
    public function show(Campaign $campaign): View
    {
        $campaign->load([
            'donations' => fn ($query) => $query->latest('donated_at')->limit(10),
            'donations.donor',
            'pledges' => fn ($query) => $query->latest()->limit(10),
            'pledges.donor',
        ]);

        // Calculate actual amount raised
        $totalRaised = $campaign->donations()
            ->where('payment_status', 'completed')
            ->sum('amount_base');

        return view('campaigns.show', compact('campaign', 'totalRaised'));
    }

    /**
     * Show the form for editing the specified campaign.
     */
    public function edit(Campaign $campaign): View
    {
        return view('campaigns.edit', compact('campaign'));
    }

    /**
     * Update the specified campaign in storage.
     */
    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $validated = $this->validateCampaign($request, $campaign->id);

        $campaign->update($validated);

        return redirect()->route('campaigns.show', $campaign)
            ->with('status', 'Campaign updated successfully.');
    }

    /**
     * Remove the specified campaign from storage.
     */
    public function destroy(Campaign $campaign): RedirectResponse
    {
        $campaign->delete();

        return redirect()->route('campaigns.index')
            ->with('status', 'Campaign deleted successfully.');
    }

    /**
     * Validate the campaign data.
     */
    private function validateCampaign(Request $request, ?int $campaignId = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('campaigns', 'code')->ignore($campaignId),
            ],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:general,event,emergency'],
            'goal_amount' => ['required', 'numeric', 'min:0'],
            'goal_currency' => ['required', 'string', 'size:3'],
            'status' => ['required', 'in:draft,active,completed,cancelled'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_public' => ['sometimes', 'boolean'],
        ]);

        $validated['is_public'] = $request->boolean('is_public');

        return $validated;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donor;
use App\Models\Pledge;
use Illuminate\Http\Request;

class PledgeController extends Controller
{
    public function index(Request $request)
    {
        $query = Pledge::with(['donor', 'campaign'])->orderByDesc('created_at');

        if ($request->filled('donor_id')) {
            $query->where('donor_id', $request->donor_id);
        }
        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pledges   = $query->paginate(20)->withQueryString();
        $campaigns = Campaign::orderBy('name')->get();
        $donors    = Donor::orderBy('first_name')->get();

        return view('pledges.index', compact('pledges', 'campaigns', 'donors'));
    }

    public function create(Request $request)
    {
        $campaigns    = Campaign::orderBy('name')->get();
        $donors       = Donor::orderBy('first_name')->get();
        $selectedDonor = $request->filled('donor_id') ? Donor::find($request->donor_id) : null;

        return view('pledges.create', compact('campaigns', 'donors', 'selectedDonor'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_id'        => ['required', 'exists:donors,id'],
            'campaign_id'     => ['nullable', 'exists:campaigns,id'],
            'pledged_amount'  => ['required', 'numeric', 'min:0.01'],
            'currency'        => ['required', 'in:USD,GBP,EUR,ETB'],
            'due_date'        => ['nullable', 'date'],
            'notes'           => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['fulfilled_amount'] = 0;
        $validated['status']           = 'pending';

        Pledge::create($validated);

        return redirect()->route('pledges.index')
            ->with('status', 'Pledge created successfully.');
    }

    public function show(Pledge $pledge)
    {
        $pledge->load(['donor', 'campaign']);

        return view('pledges.show', compact('pledge'));
    }

    public function update(Request $request, Pledge $pledge)
    {
        $validated = $request->validate([
            'fulfilled_amount' => ['required', 'numeric', 'min:0'],
            'status'           => ['required', 'in:pending,partial,fulfilled,cancelled'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ]);

        $pledge->update($validated);

        return redirect()->route('pledges.show', $pledge)
            ->with('status', 'Pledge updated successfully.');
    }

    public function destroy(Pledge $pledge)
    {
        $pledge->delete();

        return redirect()->route('pledges.index')
            ->with('status', 'Pledge deleted.');
    }
}

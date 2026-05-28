<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\DonorPreference;
use App\Models\DonorProfileHistory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class DonorController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $donors = Donor::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($q) use ($search): void {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('organization_name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('donors.index', compact('donors', 'search'));
    }

    public function create(): View
    {
        return view('donors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateDonor($request);
        $preferenceData = $this->extractPreferenceData($validated);

        $donor = Donor::create($validated);

        DonorPreference::updateOrCreate(
            ['donor_id' => $donor->id],
            $preferenceData,
        );

        DonorProfileHistory::create([
            'donor_id' => $donor->id,
            'updated_by' => auth()->id(),
            'changes' => ['created' => $donor->only(array_keys($validated))],
        ]);

        return redirect()->route('donors.show', $donor)
            ->with('status', 'Donor profile created successfully.');
    }

    public function show(Donor $donor): View
    {
        $donor->load([
            'preference',
            'donations' => fn ($query) => $query->latest('donated_at')->limit(10),
            'communicationLogs' => fn ($query) => $query->latest('sent_at')->limit(10),
            'profileHistories.updater' => fn ($query) => $query->latest()->limit(20),
        ]);

        return view('donors.show', compact('donor'));
    }

    public function edit(Donor $donor): View
    {
        $donor->load('preference');

        return view('donors.edit', compact('donor'));
    }

    public function update(Request $request, Donor $donor): RedirectResponse
    {
        $validated = $this->validateDonor($request, $donor->id);
        $preferenceData = $this->extractPreferenceData($validated);

        $original = $donor->only(array_keys($validated));
        $donor->fill($validated);
        $changes = $donor->getDirty();
        $donor->save();

        DonorPreference::updateOrCreate(
            ['donor_id' => $donor->id],
            $preferenceData,
        );

        if ($changes !== []) {
            DonorProfileHistory::create([
                'donor_id' => $donor->id,
                'updated_by' => auth()->id(),
                'changes' => [
                    'before' => array_intersect_key($original, $changes),
                    'after' => $changes,
                ],
            ]);
        }

        return redirect()->route('donors.show', $donor)
            ->with('status', 'Donor profile updated successfully.');
    }

    public function destroy(Donor $donor): RedirectResponse
    {
        $donor->delete();

        return redirect()->route('donors.index')
            ->with('status', 'Donor profile deleted successfully.');
    }

    private function validateDonor(Request $request, ?int $donorId = null): array
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'organization_name' => ['nullable', 'string', 'max:150'],
            'donor_type' => ['required', 'in:individual,corporate,foundation'],
            'category' => ['required', 'in:regular,recurring,vip,major'],
            'lifecycle_stage' => ['required', 'in:new,active,inactive'],
            'email' => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('donors', 'email')->ignore($donorId),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'preferred_channel' => ['nullable', 'in:email,sms,both'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'max:100'],
            'accepts_email' => ['sometimes', 'boolean'],
            'accepts_sms' => ['sometimes', 'boolean'],
            'newsletter_opt_in' => ['sometimes', 'boolean'],
            'campaign_interests' => ['nullable', 'array'],
            'campaign_interests.*' => ['string', 'max:100'],
            'preferred_language' => ['nullable', 'string', 'max:10'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['accepts_email'] = $request->boolean('accepts_email');
        $validated['accepts_sms'] = $request->boolean('accepts_sms');
        $validated['newsletter_opt_in'] = $request->boolean('newsletter_opt_in');

        return $validated;
    }

    private function extractPreferenceData(array &$validated): array
    {
        $preferenceFields = [
            'accepts_email',
            'accepts_sms',
            'newsletter_opt_in',
            'campaign_interests',
            'preferred_language',
        ];

        $preferences = [];

        foreach ($preferenceFields as $field) {
            if (array_key_exists($field, $validated)) {
                $preferences[$field] = $validated[$field];
                unset($validated[$field]);
            }
        }

        return $preferences;
    }
}

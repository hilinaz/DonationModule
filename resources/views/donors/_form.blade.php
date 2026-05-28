@php
    $selectedInterests = old('interests', $donor->interests ?? []);
    $selectedCampaignInterests = old('campaign_interests', $donor->preference?->campaign_interests ?? []);
    $interestOptions = ['Health', 'Education', 'Emergency', 'Community', 'Child Support', 'Environment'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="first_name" :value="__('First Name')" />
        <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $donor->first_name ?? '')" required />
        <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
    </div>

    <div>
        <x-input-label for="last_name" :value="__('Last Name')" />
        <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $donor->last_name ?? '')" required />
        <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
    </div>

    <div>
        <x-input-label for="organization_name" :value="__('Organization Name')" />
        <x-text-input id="organization_name" name="organization_name" type="text" class="mt-1 block w-full" :value="old('organization_name', $donor->organization_name ?? '')" />
    </div>

    <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $donor->email ?? '')" />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>

    <div>
        <x-input-label for="phone" :value="__('Phone')" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $donor->phone ?? '')" />
    </div>

    <div>
        <x-input-label for="address" :value="__('Address')" />
        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $donor->address ?? '')" />
    </div>

    <div>
        <x-input-label for="donor_type" :value="__('Donor Segment')" />
        <select id="donor_type" name="donor_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @foreach (['individual' => 'Individual', 'corporate' => 'Corporate', 'foundation' => 'Foundation'] as $value => $label)
                <option value="{{ $value }}" @selected(old('donor_type', $donor->donor_type ?? 'individual') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <x-input-label for="category" :value="__('Donor Category')" />
        <select id="category" name="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @foreach (['regular' => 'Regular', 'recurring' => 'Recurring', 'vip' => 'VIP', 'major' => 'Major'] as $value => $label)
                <option value="{{ $value }}" @selected(old('category', $donor->category ?? 'regular') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <x-input-label for="lifecycle_stage" :value="__('Lifecycle Stage')" />
        <select id="lifecycle_stage" name="lifecycle_stage" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @foreach (['new' => 'New', 'active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                <option value="{{ $value }}" @selected(old('lifecycle_stage', $donor->lifecycle_stage ?? 'new') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <x-input-label for="preferred_channel" :value="__('Preferred Communication Channel')" />
        <select id="preferred_channel" name="preferred_channel" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @foreach (['' => 'Not selected', 'email' => 'Email', 'sms' => 'SMS', 'both' => 'Both'] as $value => $label)
                <option value="{{ $value }}" @selected(old('preferred_channel', $donor->preferred_channel ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mt-4">
    <x-input-label :value="__('Interests (Campaign Types)')" />
    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2">
        @foreach($interestOptions as $interest)
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="interests[]" value="{{ $interest }}" @checked(in_array($interest, $selectedInterests, true))>
                <span>{{ $interest }}</span>
            </label>
        @endforeach
    </div>
</div>

<div class="mt-6 border-t pt-4">
    <h3 class="font-semibold text-lg">Preferences</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="accepts_email" value="1" @checked(old('accepts_email', $donor->preference?->accepts_email ?? true))>
            <span>Accept Email</span>
        </label>
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="accepts_sms" value="1" @checked(old('accepts_sms', $donor->preference?->accepts_sms ?? false))>
            <span>Accept SMS</span>
        </label>
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="newsletter_opt_in" value="1" @checked(old('newsletter_opt_in', $donor->preference?->newsletter_opt_in ?? false))>
            <span>Newsletter Opt-in</span>
        </label>
        <div>
            <x-input-label for="preferred_language" :value="__('Preferred Language')" />
            <x-text-input id="preferred_language" name="preferred_language" type="text" class="mt-1 block w-full" :value="old('preferred_language', $donor->preference?->preferred_language ?? 'en')" />
        </div>
    </div>

    <div class="mt-4">
        <x-input-label :value="__('Preferred Campaign Interests')" />
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2">
            @foreach($interestOptions as $interest)
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="campaign_interests[]" value="{{ $interest }}" @checked(in_array($interest, $selectedCampaignInterests, true))>
                    <span>{{ $interest }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>

<div class="mt-4">
    <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $donor->is_active ?? true))>
        <span>Active Donor</span>
    </label>
</div>

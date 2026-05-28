@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Campaign Name -->
    <div>
        <x-input-label for="name" :value="__('Campaign Name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $campaign->name ?? '')" required autofocus placeholder="e.g. Winter Relief Campaign" />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <!-- Campaign Code -->
    <div>
        <x-input-label for="code" :value="__('Campaign Code')" />
        <x-text-input id="code" name="code" type="text" class="mt-1 block w-full uppercase" :value="old('code', $campaign->code ?? '')" required placeholder="e.g. WINTER-2026" />
        <x-input-error class="mt-2" :messages="$errors->get('code')" />
    </div>

    <!-- Type -->
    <div>
        <x-input-label for="type" :value="__('Campaign Type')" />
        <select id="type" name="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
            <option value="general" @selected(old('type', $campaign->type ?? 'general') === 'general')>General</option>
            <option value="event" @selected(old('type', $campaign->type ?? '') === 'event')>Event</option>
            <option value="emergency" @selected(old('type', $campaign->type ?? '') === 'emergency')>Emergency</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('type')" />
    </div>

    <!-- Status -->
    <div>
        <x-input-label for="status" :value="__('Status')" />
        <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
            <option value="draft" @selected(old('status', $campaign->status ?? 'draft') === 'draft')>Draft</option>
            <option value="active" @selected(old('status', $campaign->status ?? '') === 'active')>Active</option>
            <option value="completed" @selected(old('status', $campaign->status ?? '') === 'completed')>Completed</option>
            <option value="cancelled" @selected(old('status', $campaign->status ?? '') === 'cancelled')>Cancelled</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('status')" />
    </div>

    <!-- Goal Amount -->
    <div>
        <x-input-label for="goal_amount" :value="__('Goal Amount')" />
        <x-text-input id="goal_amount" name="goal_amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('goal_amount', $campaign->goal_amount ?? '0.00')" required placeholder="0.00" />
        <x-input-error class="mt-2" :messages="$errors->get('goal_amount')" />
    </div>

    <!-- Goal Currency -->
    <div>
        <x-input-label for="goal_currency" :value="__('Goal Currency')" />
        <select id="goal_currency" name="goal_currency" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
            <option value="USD" @selected(old('goal_currency', $campaign->goal_currency ?? 'USD') === 'USD')>USD ($)</option>
            <option value="EUR" @selected(old('goal_currency', $campaign->goal_currency ?? '') === 'EUR')>EUR (€)</option>
            <option value="GBP" @selected(old('goal_currency', $campaign->goal_currency ?? '') === 'GBP')>GBP (£)</option>
            <option value="CAD" @selected(old('goal_currency', $campaign->goal_currency ?? '') === 'CAD')>CAD ($)</option>
            <option value="AUD" @selected(old('goal_currency', $campaign->goal_currency ?? '') === 'AUD')>AUD ($)</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('goal_currency')" />
    </div>

    <!-- Starts At -->
    <div>
        <x-input-label for="starts_at" :value="__('Starts At')" />
        <x-text-input id="starts_at" name="starts_at" type="date" class="mt-1 block w-full" :value="old('starts_at', isset($campaign->starts_at) ? $campaign->starts_at->format('Y-m-d') : '')" />
        <x-input-error class="mt-2" :messages="$errors->get('starts_at')" />
    </div>

    <!-- Ends At -->
    <div>
        <x-input-label for="ends_at" :value="__('Ends At')" />
        <x-text-input id="ends_at" name="ends_at" type="date" class="mt-1 block w-full" :value="old('ends_at', isset($campaign->ends_at) ? $campaign->ends_at->format('Y-m-d') : '')" />
        <x-input-error class="mt-2" :messages="$errors->get('ends_at')" />
    </div>
</div>

<!-- Description -->
<div class="mt-6">
    <x-input-label for="description" :value="__('Description')" />
    <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Provide a detailed description of the campaign objectives, target audience, and fund utilization plan...">{{ old('description', $campaign->description ?? '') }}</textarea>
    <x-input-error class="mt-2" :messages="$errors->get('description')" />
</div>

<!-- Is Public Visibility -->
<div class="mt-6 flex items-center">
    <input type="checkbox" id="is_public" name="is_public" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked(old('is_public', $campaign->is_public ?? false))>
    <label for="is_public" class="ms-2 text-sm text-gray-600 font-medium">
        {{ __('Make this campaign public (visible to donors on portal)') }}
    </label>
    <x-input-error class="mt-2" :messages="$errors->get('is_public')" />
</div>

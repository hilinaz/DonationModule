<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Donor;
use App\Services\DonationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_donation_and_calculates_base_amount(): void
    {
        $donor = Donor::factory()->create();
        $campaign = Campaign::factory()->create();

        $donation = app(DonationService::class)->create([
            'donor_id' => $donor->id,
            'campaign_id' => $campaign->id,
            'currency' => 'EUR',
            'amount_original' => 100,
            'exchange_rate' => 1.2,
            'donated_at' => now(),
        ]);

        $this->assertSame('EUR', $donation->currency);
        $this->assertSame('120.00', $donation->amount_base);
        $this->assertSame('pending', $donation->payment_status);
        $this->assertSame('one_time', $donation->donation_type);
    }
}

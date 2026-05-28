<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and permissions for Spatie middleware
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_unauthorized_user_cannot_access_campaigns(): void
    {
        // Create a regular user without the Fundraising Manager role
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('campaigns.index'));

        $response->assertStatus(403);
    }

    public function test_fundraising_manager_can_view_campaigns_list(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('Fundraising Manager');

        // Create some campaigns
        Campaign::factory()->count(3)->create();

        $response = $this
            ->actingAs($manager)
            ->get(route('campaigns.index'));

        $response->assertOk();
        $response->assertViewHas('campaigns');
    }

    public function test_fundraising_manager_can_create_campaign(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('Fundraising Manager');

        $campaignData = [
            'name' => 'Save the Oceans',
            'code' => 'OCEAN-2026',
            'type' => 'emergency',
            'goal_amount' => 50000.00,
            'goal_currency' => 'USD',
            'status' => 'active',
            'starts_at' => now()->format('Y-m-d'),
            'ends_at' => now()->addDays(30)->format('Y-m-d'),
            'description' => 'A campaign to clean up plastic from our oceans.',
            'is_public' => '1',
        ];

        $response = $this
            ->actingAs($manager)
            ->post(route('campaigns.store'), $campaignData);

        $this->assertDatabaseHas('campaigns', [
            'name' => 'Save the Oceans',
            'code' => 'OCEAN-2026',
            'goal_amount' => '50000.00',
            'is_public' => true,
        ]);

        $campaign = Campaign::where('code', 'OCEAN-2026')->first();
        $response->assertRedirect(route('campaigns.show', $campaign));
    }

    public function test_fundraising_manager_can_update_campaign(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('Fundraising Manager');

        $campaign = Campaign::factory()->create([
            'name' => 'Old Campaign Name',
            'code' => 'OLD-CODE',
        ]);

        $updateData = [
            'name' => 'New Campaign Name',
            'code' => 'NEW-CODE',
            'type' => 'general',
            'goal_amount' => 125000.00,
            'goal_currency' => 'EUR',
            'status' => 'completed',
            'starts_at' => now()->format('Y-m-d'),
            'ends_at' => now()->addDays(30)->format('Y-m-d'),
            'description' => 'Updated campaign description.',
            'is_public' => '0',
        ];

        $response = $this
            ->actingAs($manager)
            ->patch(route('campaigns.update', $campaign), $updateData);

        $response->assertRedirect(route('campaigns.show', $campaign));

        $campaign->refresh();
        $this->assertSame('New Campaign Name', $campaign->name);
        $this->assertSame('NEW-CODE', $campaign->code);
        $this->assertSame('completed', $campaign->status);
        $this->assertFalse($campaign->is_public);
    }

    public function test_fundraising_manager_can_soft_delete_campaign(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('Fundraising Manager');

        $campaign = Campaign::factory()->create();

        $response = $this
            ->actingAs($manager)
            ->delete(route('campaigns.destroy', $campaign));

        $response->assertRedirect(route('campaigns.index'));
        $this->assertSoftDeleted($campaign);
    }
}

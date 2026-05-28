<?php

namespace App\Console\Commands;

use App\Models\Donor;
use App\Services\DonorLifecycleService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:sync-donor-lifecycle')]
#[Description('Recalculate donor lifecycle and engagement score')]
class SyncDonorLifecycle extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(DonorLifecycleService $lifecycleService): int
    {
        $this->info('Syncing donor lifecycle stages...');

        $total = 0;

        Donor::query()
            ->with('donations')
            ->chunkById(100, function ($donors) use ($lifecycleService, &$total): void {
                foreach ($donors as $donor) {
                    $lifecycleService->refresh($donor);
                    $total++;
                }
            });

        $this->info("Lifecycle sync completed for {$total} donor(s).");

        return self::SUCCESS;
    }
}

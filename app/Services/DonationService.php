<?php

namespace App\Services;

use App\Models\Donation;

class DonationService
{
    public function create(array $data): Donation
    {
        $exchangeRate = (float) ($data['exchange_rate'] ?? 1);
        $amountOriginal = (float) ($data['amount_original'] ?? 0);

        $data['exchange_rate'] = $exchangeRate;
        $data['amount_base'] = round($amountOriginal * $exchangeRate, 2);
        $data['base_currency'] = $data['base_currency'] ?? 'USD';
        $data['payment_status'] = $data['payment_status'] ?? 'pending';
        $data['donation_type'] = $data['donation_type'] ?? 'one_time';
        $data['source'] = $data['source'] ?? 'offline';

        return Donation::create($data);
    }
}

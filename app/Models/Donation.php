<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'donor_id',
        'campaign_id',
        'donation_type',
        'source',
        'payment_status',
        'currency',
        'amount_original',
        'exchange_rate',
        'amount_base',
        'base_currency',
        'gift_aid_eligible',
        'payment_gateway',
        'transaction_reference',
        'donated_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'amount_original' => 'decimal:2',
            'exchange_rate' => 'decimal:6',
            'amount_base' => 'decimal:2',
            'gift_aid_eligible' => 'boolean',
            'donated_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringDonation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'donor_id',
        'campaign_id',
        'amount',
        'currency',
        'frequency',
        'status',
        'next_charge_at',
        'last_charge_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'next_charge_at' => 'datetime',
            'last_charge_at' => 'datetime',
            'ends_at' => 'datetime',
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

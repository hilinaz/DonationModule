<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonorPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'accepts_email',
        'accepts_sms',
        'newsletter_opt_in',
        'campaign_interests',
        'preferred_language',
    ];

    protected function casts(): array
    {
        return [
            'accepts_email' => 'boolean',
            'accepts_sms' => 'boolean',
            'newsletter_opt_in' => 'boolean',
            'campaign_interests' => 'array',
        ];
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }
}

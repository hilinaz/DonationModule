<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'organization_name',
        'donor_type',
        'category',
        'lifecycle_stage',
        'email',
        'phone',
        'preferred_channel',
        'address',
        'interests',
        'engagement_score',
        'last_engaged_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'interests' => 'array',
            'last_engaged_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function recurringDonations(): HasMany
    {
        return $this->hasMany(RecurringDonation::class);
    }

    public function pledges(): HasMany
    {
        return $this->hasMany(Pledge::class);
    }

    public function communicationLogs(): HasMany
    {
        return $this->hasMany(CommunicationLog::class);
    }

    public function profileHistories(): HasMany
    {
        return $this->hasMany(DonorProfileHistory::class);
    }

    public function preference(): HasOne
    {
        return $this->hasOne(DonorPreference::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pledge extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'donor_id',
        'campaign_id',
        'pledged_amount',
        'fulfilled_amount',
        'currency',
        'due_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'pledged_amount' => 'decimal:2',
            'fulfilled_amount' => 'decimal:2',
            'due_date' => 'date',
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

<?php

namespace App\Models;

use App\Observers\IncidentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(IncidentObserver::class)]
class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_id',
        'infrastructure_id',
        'type',
        'status',
        'description',
        'location',
        'photo_path',
        'reported_at',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'reported_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function infrastructure(): BelongsTo
    {
        return $this->belongsTo(Infrastructure::class);
    }

    public function interventions(): HasMany
    {
        return $this->hasMany(Intervention::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }
}

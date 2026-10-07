<?php

namespace App\Observers;

use App\Models\Alert;
use App\Models\Zone;

class ZoneObserver
{
    public function created(Zone $zone): void
    {
        $this->alertIfHighRisk($zone, "La zone « {$zone->name} » est créée avec un niveau de risque élevé.");
    }

    public function updated(Zone $zone): void
    {
        if ($zone->wasChanged('risk_level')) {
            $this->alertIfHighRisk($zone, "La zone « {$zone->name} » est passée en niveau de risque élevé.");
        }
    }

    private function alertIfHighRisk(Zone $zone, string $message): void
    {
        if ($zone->risk_level !== 'high') {
            return;
        }

        Alert::create([
            'zone_id' => $zone->id,
            'type' => 'Risque élevé',
            'severity' => 'critical',
            'message' => $message,
        ]);
    }
}

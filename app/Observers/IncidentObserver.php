<?php

namespace App\Observers;

use App\Models\Alert;
use App\Models\Incident;
use Illuminate\Support\Str;

class IncidentObserver
{
    /** mot-clé du type d'incident => [libellé d'alerte, gravité] */
    private const RULES = [
        'fuite' => ['Fuite', 'high'],
        'contamination' => ['Contamination', 'critical'],
        'coupure' => ["Coupure d'eau", 'high'],
    ];

    public function created(Incident $incident): void
    {
        $type = Str::lower($incident->type);

        foreach (self::RULES as $keyword => [$label, $severity]) {
            if (Str::contains($type, $keyword)) {
                Alert::create([
                    'zone_id' => $incident->zone_id,
                    'incident_id' => $incident->id,
                    'type' => $label,
                    'severity' => $severity,
                    'message' => "{$label} signalée : {$incident->description}",
                ]);

                return;
            }
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Technicien extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'speciality',
        'status',
    ];

    public function interventions(): HasMany
    {
        return $this->hasMany(Intervention::class, 'technician_id');
    }
}

<?php

namespace App\Http\Requests;

use App\Models\Alert;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'zone_id' => ['required', 'exists:zones,id'],
            'incident_id' => ['nullable', 'exists:incidents,id'],
            'type' => ['required', 'string', 'max:100'],
            'severity' => ['required', Rule::in(array_keys(Alert::SEVERITIES))],
            'message' => ['required', 'string', 'max:1000'],
            'read' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'zone_id.required' => 'Choisissez la zone concernée.',
            'zone_id.exists' => 'Cette zone n’existe pas.',
            'incident_id.exists' => 'Cet incident n’existe pas.',
            'type.required' => 'Le type d’alerte est obligatoire.',
            'type.max' => 'Le type d’alerte ne peut pas dépasser 100 caractères.',
            'severity.required' => 'Choisissez une gravité.',
            'severity.in' => 'Gravité invalide.',
            'message.required' => 'Le message de l’alerte est obligatoire.',
            'message.max' => 'Le message ne peut pas dépasser 1000 caractères.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportMalfunctionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'malfunction_type' => ['required', 'string', 'max:150'],
            'priority' => ['required', 'in:medium,high,critical'],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'reporter_name' => ['nullable', 'string', 'min:2', 'max:150', 'regex:/^[\p{L}\s\-\'.]+$/u'],
            'reporter_phone' => ['nullable', 'string', 'regex:/^[0-9\+\-\s\(\)]{8,25}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'malfunction_type.required' => 'Veuillez sélectionner le type d’anomalie ou de dysfonctionnement constaté.',
            'priority.required' => 'Veuillez évaluer le niveau de gravité ou d’urgence de la situation.',
            'priority.in' => 'Le niveau de gravité sélectionné est invalide.',
            'description.required' => 'Veuillez décrire le dysfonctionnement observé sur l’ouvrage.',
            'description.min' => 'La description doit comporter au moins :min caractères pour permettre aux techniciens de cibler leur intervention.',
            'description.max' => 'La description ne peut pas dépasser :max caractères.',
            'reporter_name.min' => 'Le nom du déclarant doit comporter au moins :min caractères.',
            'reporter_name.regex' => 'Le nom du déclarant ne doit contenir que des lettres, espaces ou tirets.',
            'reporter_phone.regex' => 'Le numéro de téléphone saisi n’est pas valide (ex: +216 71 888 101 ou 98 123 456).',
        ];
    }
}

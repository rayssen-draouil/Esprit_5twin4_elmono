<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return ['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'max:255'], 'role' => ['required', Rule::in(['admin', 'manager', 'gestionnaire', 'citizen'])], 'password' => ['required', 'confirmed', 'min:8']];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(['TODO', 'IN_PROGRESS', 'COMPLETED'])],
            'importance' => 'required|integer|min:1|max:5',
            'deadline' => 'required|date_format:Y-m-d H:i:s|after_or_equal:now',
        ];
    }
}

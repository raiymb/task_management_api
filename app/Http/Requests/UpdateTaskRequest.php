<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status' => ['sometimes', 'required', Rule::in(['TODO', 'IN_PROGRESS', 'COMPLETED'])],
            'importance' => 'sometimes|required|integer|min:1|max:5',
            'deadline' => 'sometimes|required|date_format:Y-m-d H:i:s',
        ];
    }
}

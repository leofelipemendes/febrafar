<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskResquest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'task_name' => [
                'required',
                'string',
                'max:100'
            ],
            'task_description' => [
                'required',
                'string',
                'max:255'
            ],
            'start_date' => [
                'required',
                'date',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'task_name.required' => 'Please, inform a identification for this task.',
            'task_description.required' => 'Please, put some description for this task.',
            'start_date.required' => 'The start date is required.',
            'start_date.date' => 'The initial date must be a valid date like YYYY-mm-dd'
        ];
    }
}

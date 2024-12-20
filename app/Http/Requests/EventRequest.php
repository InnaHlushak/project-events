<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=> 'required|min:3|max:150',
            'category_id' => ['required','exists:categories,id'],
            'deadline' => 'required|date|after:now',
            'venue'=> 'required|min:5|max:255',
            'description'=> 'required|min:5',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:900',

            'costs' => 'required|array',
            // 'costs.*.id' => 'required|integer|exists:costs,id',
        ];
    }
}

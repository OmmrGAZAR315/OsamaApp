<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
            'id' => ['nullable'],
            'name' => ['nullable'],
            'phone' => ['nullable'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->id ?? auth()->id(),
            'name' => $this->name ?? auth()->user()->name ?? '',
            'phone' => $this->phone ?? auth()->user()->phone ?? null,
            'photo' => $this->photo ?? auth()->user()->profile_pic_path ?? null,
        ]);
    }

}

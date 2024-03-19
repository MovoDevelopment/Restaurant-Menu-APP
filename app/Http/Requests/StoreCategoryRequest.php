<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
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
        $user = auth()->user();

        return [
            'name' => 'required|min:3|max:100',
            'category_id' => 'exists:categories,id',
            'user_id' => [
                'required_if:user_type,admin',
                Rule::exists('users', 'id')->where(function ($query) use ($user) {
                    return $user->user_type === 'admin';
                }),
            ],
        ];
    }
}

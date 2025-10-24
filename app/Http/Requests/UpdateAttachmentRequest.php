<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttachmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $attachment = $this->route('attachment');
        $user = auth()->user();
        
        // Owner can always update
        if ($attachment && $attachment->uploaded_by === $user->id) {
            return true;
        }
        
        // Admins can update any attachment
        if ($user && $user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => 'required|string|max:255|in:document,certificate,transcript,diploma,photo,presentation,portfolio,assignment,research,lesson_plan,curriculum,resume,recommendation,other',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category.required' => 'Please select a category for your file.',
            'category.in' => 'Please select a valid category.',
            'description.max' => 'The description must not exceed 1000 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'is_public' => 'public visibility',
        ];
    }
}
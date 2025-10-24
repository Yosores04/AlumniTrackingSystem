<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttachmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:10240', // 10MB max
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,zip,rar,txt'
            ],
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
            'file.required' => 'Please select a file to upload.',
            'file.max' => 'The file size must not exceed 10MB.',
            'file.mimes' => 'Only PDF, Word documents, Excel sheets, PowerPoint presentations, images (JPG, PNG, GIF), ZIP archives, and text files are allowed.',
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
            'file' => 'attachment file',
            'is_public' => 'public visibility',
        ];
    }
}
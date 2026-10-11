<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'program_id' => 'sometimes|required|exists:programs,id',
            'academic_year_id' => 'sometimes|required|exists:academic_years,id',
            'semester_id' => 'sometimes|required|exists:semesters,id',
            'subject_id' => 'sometimes|required|exists:subjects,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx|max:10240',
            'status' => 'sometimes|boolean',
        ];
    }
}

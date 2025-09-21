<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'resident_id' => 'required|exists:residents,id',
            'report_category_id' => 'required|exists:report_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image',
            'address' => 'nullable|string',
            // latitude/longitude removed
        ];
    }
}

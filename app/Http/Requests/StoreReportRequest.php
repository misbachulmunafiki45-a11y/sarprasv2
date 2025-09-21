<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Izinkan semua pengguna terautentikasi melalui middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // resident_id diisi di controller dari user terautentikasi, tidak perlu divalidasi dari input
            // 'resident_id' => 'sometimes|nullable|exists:residents,id',
            'report_category_id' => 'required|exists:report_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image',
            // Relax location rules to avoid blocking submission when location isn't provided by the UI yet
            // latitude/longitude removed
            'address' => 'required|string'
        ];
    }
}

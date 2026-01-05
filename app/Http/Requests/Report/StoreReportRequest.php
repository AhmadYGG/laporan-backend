<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // wajib true
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'location'    => 'required|string|regex:/^-?\d+\.?\d*,-?\d+\.?\d*$/',
            'photo'       => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Judul laporan wajib diisi',
            'title.string'         => 'Judul harus berupa teks',
            'title.max'            => 'Judul maksimal 255 karakter',

            'description.required' => 'Deskripsi wajib diisi',
            'description.string'   => 'Deskripsi harus berupa teks',

            'location.required'    => 'Lokasi wajib dipilih dari peta',
            'location.string'      => 'Lokasi harus berupa teks',
            'location.regex'       => 'Format lokasi tidak valid',

            'photo.image'          => 'Foto harus berupa file gambar',
            'photo.max'            => 'Ukuran foto maksimal 2MB',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Return JSON for API requests, redirect for web
        if ($this->expectsJson() || $this->is('api/*')) {
            throw new HttpResponseException(
                response()->json([
                    'errors'  => $validator->errors(),
                    'message' => 'Validation failed',
                ], 422)
            );
        }

        // Default Laravel behavior for web (redirect back with errors)
        parent::failedValidation($validator);
    }
}

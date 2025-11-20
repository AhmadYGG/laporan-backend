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
            'location'    => 'required|string',
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

            'location.required'    => 'Lokasi wajib diisi',
            'location.string'      => 'Lokasi harus berupa teks',

            'photo.image'          => 'Foto harus berupa file gambar',
            'photo.max'            => 'Ukuran foto maksimal 2MB',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors'  => $validator->errors(),
                'message' => 'Validation failed',
            ], 422)
        );
    }
}

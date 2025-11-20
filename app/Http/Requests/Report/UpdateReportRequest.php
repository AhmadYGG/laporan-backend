<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // wajib true
    }

    public function rules(): array
    {
        return [
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'location'    => 'sometimes|string',
            // 'photo'       => 'sometimes|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.string'         => 'Judul harus berupa teks',
            'title.max'            => 'Judul maksimal 255 karakter',

            'description.string'   => 'Deskripsi harus berupa teks',

            'location.string'      => 'Lokasi harus berupa teks',

            // 'photo.image'          => 'Foto harus berupa file gambar',
            // 'photo.max'            => 'Ukuran foto maksimal 2MB',
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

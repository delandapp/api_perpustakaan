<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddBukuRequests extends FormRequest
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
            'Judul' => 'required|unique:Bukus',
            'Deskripsi' => 'required|min:40',
            'Penulis' => 'required|min:5',
            'Penerbit' => 'required|min:5',
            'TahunTerbit' => 'required|min:4',
            'JumlahHalaman' => 'required',
            'CoverBuku' => 'required|image|max:2048',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response(["Status" => 400,"Message" => $validator->getMessageBag()], 400));
    }
}

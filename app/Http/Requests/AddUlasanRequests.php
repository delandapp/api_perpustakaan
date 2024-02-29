<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddUlasanRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'Rating' => ['required','max:1'],
            'BukuID' => ['required'],
            'Ulasan' => ['max:255', 'required']
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response(["Status" => 400,"Message" => $validator->getMessageBag()], 400));
    }
}

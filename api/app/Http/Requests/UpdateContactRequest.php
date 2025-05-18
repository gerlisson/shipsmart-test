<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateContactRequest",
 *     @OA\Property(property="name", type="string", example="João Atualizado"),
 *     @OA\Property(property="email", type="string", format="email", example="joao@novoemail.com"),
 *     @OA\Property(property="phone", type="string", example="11988887777"),
 *     @OA\Property(property="zip_code", type="string", example="01002-000"),
 *     @OA\Property(property="state", type="string", example="SP"),
 *     @OA\Property(property="city", type="string", example="Campinas"),
 *     @OA\Property(property="neighborhood", type="string", example="Centro Novo"),
 *     @OA\Property(property="address", type="string", example="Rua Nova"),
 *     @OA\Property(property="number", type="string", example="456")
 * )
 */
class UpdateContactRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email',
            'phone' => 'sometimes|required',
            'zip_code' => 'sometimes|required',
            'state' => 'sometimes|required',
            'city' => 'sometimes|required',
            'neighborhood' => 'sometimes|required',
            'address' => 'sometimes|required',
            'number' => 'sometimes|required'
        ];
    }
}

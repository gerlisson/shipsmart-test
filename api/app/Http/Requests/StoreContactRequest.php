<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreContactRequest",
 *     required={"name", "email", "phone", "zip_code", "state", "city", "neighborhood", "address", "number"},
 *     @OA\Property(property="name", type="string", example="João Silva"),
 *     @OA\Property(property="email", type="string", format="email", example="joao@email.com"),
 *     @OA\Property(property="phone", type="string", example="11999999999"),
 *     @OA\Property(property="zip_code", type="string", example="01001-000"),
 *     @OA\Property(property="state", type="string", example="SP"),
 *     @OA\Property(property="city", type="string", example="São Paulo"),
 *     @OA\Property(property="neighborhood", type="string", example="Centro"),
 *     @OA\Property(property="address", type="string", example="Rua Teste"),
 *     @OA\Property(property="number", type="string", example="123")
 * )
 */
class StoreContactRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required',
            'zip_code' => 'required',
            'state' => 'required',
            'city' => 'required',
            'neighborhood' => 'required',
            'address' => 'required',
            'number' => 'required',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Autorizar si el usuario está autenticado
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:191',
            'apellido' => 'nullable|string|max:191',
            'telefono' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191|unique:clientes,email',
            'direccion' => 'nullable|string|max:191',
            'tipo_cliente' => 'required|in:minorista,mayorista,especial',
            'limite_credito' => 'nullable|numeric|min:0|max:999999.99',
            'activo' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del cliente es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder 191 caracteres.',
            'email.email' => 'El email debe ser una dirección válida.',
            'email.unique' => 'Este email ya está registrado.',
            'tipo_cliente.required' => 'Debe seleccionar un tipo de cliente.',
            'tipo_cliente.in' => 'El tipo de cliente no es válido.',
            'limite_credito.numeric' => 'El límite de crédito debe ser un número.',
            'limite_credito.min' => 'El límite de crédito no puede ser negativo.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'apellido' => 'apellido',
            'telefono' => 'teléfono',
            'email' => 'correo electrónico',
            'tipo_cliente' => 'tipo de cliente',
            'limite_credito' => 'límite de crédito',
        ];
    }
}

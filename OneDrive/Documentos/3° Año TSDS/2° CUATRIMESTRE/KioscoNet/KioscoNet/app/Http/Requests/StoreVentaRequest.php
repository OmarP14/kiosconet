<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVentaRequest extends FormRequest
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
            // Campos principales
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_venta' => 'nullable|date',
            'lista_precios' => 'required|in:minorista,mayorista,especial',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,cuenta_corriente,cc,mixto',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',

            // Validaciones condicionales según método de pago
            'monto_recibido' => 'required_if:metodo_pago,efectivo|nullable|numeric|min:0',
            'tipo_tarjeta' => 'required_if:metodo_pago,tarjeta|nullable|in:debito,credito',
            'ultimos_digitos' => 'required_if:metodo_pago,tarjeta|nullable|digits:4',
            'codigo_autorizacion' => 'required_if:metodo_pago,tarjeta|nullable|string|max:50',
            'numero_transferencia' => 'required_if:metodo_pago,transferencia|nullable|string|max:50',
            'banco' => 'required_if:metodo_pago,transferencia|nullable|string|max:100',
            'pagos_mixtos' => 'required_if:metodo_pago,mixto|nullable|array|min:1',
            'pagos_mixtos.*.metodo' => 'required_with:pagos_mixtos|in:efectivo,tarjeta,transferencia',
            'pagos_mixtos.*.monto' => 'required_with:pagos_mixtos|numeric|min:0',
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
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'cliente_id.exists' => 'El cliente seleccionado no existe en el sistema.',
            'fecha_venta.date' => 'La fecha de venta debe ser una fecha válida.',
            'lista_precios.required' => 'Debe seleccionar una lista de precios.',
            'lista_precios.in' => 'La lista de precios seleccionada no es válida.',
            'metodo_pago.required' => 'Debe seleccionar un método de pago.',
            'metodo_pago.in' => 'El método de pago seleccionado no es válido.',
            'productos.required' => 'Debe agregar al menos un producto a la venta.',
            'productos.array' => 'Los productos deben ser una lista.',
            'productos.min' => 'Debe agregar al menos un producto a la venta.',
            'productos.*.id.required' => 'Cada producto debe tener un ID.',
            'productos.*.id.exists' => 'Uno o más productos no existen en el sistema.',
            'productos.*.cantidad.required' => 'Debe especificar la cantidad de cada producto.',
            'productos.*.cantidad.integer' => 'La cantidad debe ser un número entero.',
            'productos.*.cantidad.min' => 'La cantidad debe ser al menos 1.',
            'productos.*.precio.required' => 'Debe especificar el precio de cada producto.',
            'productos.*.precio.numeric' => 'El precio debe ser un número.',
            'productos.*.precio.min' => 'El precio no puede ser negativo.',
            'total.required' => 'El total de la venta es obligatorio.',
            'total.numeric' => 'El total debe ser un número.',
            'total.min' => 'El total no puede ser negativo.',
            'monto_recibido.required_if' => 'El monto recibido es obligatorio para pago en efectivo.',
            'monto_recibido.numeric' => 'El monto recibido debe ser un número.',
            'monto_recibido.min' => 'El monto recibido no puede ser negativo.',
            'tipo_tarjeta.required_if' => 'Debe seleccionar el tipo de tarjeta.',
            'tipo_tarjeta.in' => 'El tipo de tarjeta debe ser débito o crédito.',
            'ultimos_digitos.required_if' => 'Debe ingresar los últimos 4 dígitos de la tarjeta.',
            'ultimos_digitos.digits' => 'Debe ingresar exactamente 4 dígitos.',
            'codigo_autorizacion.required_if' => 'El código de autorización es obligatorio para pago con tarjeta.',
            'codigo_autorizacion.max' => 'El código de autorización no puede exceder 50 caracteres.',
            'numero_transferencia.required_if' => 'El número de transferencia es obligatorio.',
            'numero_transferencia.max' => 'El número de transferencia no puede exceder 50 caracteres.',
            'banco.required_if' => 'El nombre del banco es obligatorio para transferencias.',
            'banco.max' => 'El nombre del banco no puede exceder 100 caracteres.',
            'pagos_mixtos.required_if' => 'Debe especificar al menos un método de pago para pago mixto.',
            'pagos_mixtos.array' => 'Los pagos mixtos deben ser una lista.',
            'pagos_mixtos.min' => 'Debe especificar al menos un método de pago.',
            'pagos_mixtos.*.metodo.required_with' => 'Cada pago debe tener un método.',
            'pagos_mixtos.*.metodo.in' => 'El método de pago no es válido.',
            'pagos_mixtos.*.monto.required_with' => 'Cada pago debe tener un monto.',
            'pagos_mixtos.*.monto.numeric' => 'El monto debe ser un número.',
            'pagos_mixtos.*.monto.min' => 'El monto no puede ser negativo.',
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
            'cliente_id' => 'cliente',
            'fecha_venta' => 'fecha de venta',
            'lista_precios' => 'lista de precios',
            'metodo_pago' => 'método de pago',
            'productos' => 'productos',
            'productos.*.id' => 'ID del producto',
            'productos.*.cantidad' => 'cantidad',
            'productos.*.precio' => 'precio',
            'total' => 'total',
            'monto_recibido' => 'monto recibido',
            'tipo_tarjeta' => 'tipo de tarjeta',
            'ultimos_digitos' => 'últimos dígitos',
            'codigo_autorizacion' => 'código de autorización',
            'numero_transferencia' => 'número de transferencia',
            'banco' => 'banco',
            'pagos_mixtos' => 'pagos mixtos',
            'pagos_mixtos.*.metodo' => 'método de pago',
            'pagos_mixtos.*.monto' => 'monto',
        ];
    }
}

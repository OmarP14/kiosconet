<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoMixto extends Model
{
    protected $table = 'pagos_mixtos';
    
    protected $fillable = [
        'venta_id',
        'metodo_pago',
        'monto',
        'monto_recibido',
        'vuelto',
        'tipo_tarjeta',
        'ultimos_digitos',
        'codigo_autorizacion',
        'numero_transferencia',
        'banco',
        'fecha_transferencia',
        'hora_transferencia',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'monto_recibido' => 'decimal:2',
        'vuelto' => 'decimal:2',
        'fecha_transferencia' => 'date',
    ];

    // Relación: un pago pertenece a una venta
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
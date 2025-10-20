<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * MODELO VENTA
 * Este archivo le dice a Laravel cómo funciona una venta
 */
class Venta extends Model
{
    // El nombre de la tabla en la base de datos
    protected $table = 'ventas';
    
    // Todos los campos que podemos llenar
    protected $fillable = [
        // Información básica
        'numero',
        'usuario_id',
        'cliente_id',
        'fecha_venta',
        'lista_precios',
        
        // Dinero
        'subtotal',
        'descuento_porcentaje',
        'descuento_monto',
        'total',
        'comision',
        
        // Método de pago
        'metodo_pago',
        
        // Si pagó en efectivo
        'monto_recibido',
        'vuelto',
        
        // Si pagó con tarjeta
        'tipo_tarjeta',
        'ultimos_digitos',
        'codigo_autorizacion',
        
        // Si pagó con transferencia
        'numero_transferencia',
        'banco',
        'fecha_transferencia',
        'hora_transferencia',
        
        // Si pagó con cuenta corriente
        'saldo_anterior',
        'nuevo_saldo',
        'observaciones_cc',
        
        // Otros datos
        'observaciones',
        'estado',
        'fecha_procesamiento',
        
        // Si se anuló
        'anulada',
        'usuario_anulacion_id',
        'fecha_anulacion',
        'motivo_anulacion',
        
        // Para imprimir
        'impreso',
        'fecha_impresion',
        'cantidad_reimpresiones',
        'token_comprobante',
    ];

    // Le decimos a Laravel qué tipo de dato es cada campo
    protected $casts = [
        'fecha_venta' => 'datetime',
        'subtotal' => 'decimal:2',
        'descuento_porcentaje' => 'decimal:2',
        'descuento_monto' => 'decimal:2',
        'total' => 'decimal:2',
        'comision' => 'decimal:2',
        'monto_recibido' => 'decimal:2',
        'vuelto' => 'decimal:2',
        'saldo_anterior' => 'decimal:2',
        'nuevo_saldo' => 'decimal:2',
        'anulada' => 'boolean',
        'impreso' => 'boolean',
        'fecha_anulacion' => 'datetime',
        'fecha_transferencia' => 'datetime',
        'fecha_impresion' => 'datetime',
        'fecha_procesamiento' => 'datetime',
    ];

    /**
     * ESTO SE EJECUTA AUTOMÁTICAMENTE AL CREAR UNA VENTA
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($venta) {
            // Generar un código secreto único
            if (empty($venta->token_comprobante)) {
                $venta->token_comprobante = Str::random(40);
            }
            
            // Si no tiene fecha, usar hoy
            if (empty($venta->fecha_venta)) {
                $venta->fecha_venta = now();
            }
            
            // Si no tiene lista de precios, usar minorista
            if (empty($venta->lista_precios)) {
                $venta->lista_precios = 'minorista';
            }
        });
    }

    // ==========================================
    // RELACIONES (conectar con otras tablas)
    // ==========================================

    // Una venta pertenece a un vendedor
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }

    // Una venta pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }

    // Una venta tiene muchos productos
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'venta_id', 'id');
    }

    // Usuario que anuló la venta
    public function usuarioAnulacion()
    {
        return $this->belongsTo(Usuario::class, 'usuario_anulacion_id', 'id');
    }

    // ==========================================
    // FUNCIONES ÚTILES
    // ==========================================

    /**
     * ¿Podemos anular esta venta?
     */
    public function puedeAnularse()
    {
        // Si ya está anulada, no
        if ($this->anulada) {
            return false;
        }

        // Si pasaron más de 24 horas, no
        $fechaVenta = $this->fecha_venta ?? $this->created_at;
        $horasTranscurridas = $fechaVenta->diffInHours(now());
        if ($horasTranscurridas > 24) {
            return false;
        }

        return true;
    }

    /**
     * Marcar que ya imprimimos el ticket
     */
    public function marcarComoImpreso()
    {
        $this->update([
            'impreso' => true,
            'fecha_impresion' => now(),
        ]);
    }

    /**
     * Sumar 1 al contador de reimpresiones
     */
    public function incrementarReimpresiones()
    {
        $this->increment('cantidad_reimpresiones');
    }

    /**
     * Obtener el total con formato bonito ($1,234.56)
     */
    public function getTotalFormateadoAttribute()
    {
        return '$' . number_format($this->total, 2);
    }

    /**
     * Obtener la fecha con formato bonito (01/01/2025 10:30)
     */
    public function getFechaFormateadaAttribute()
    {
        $fecha = $this->fecha_venta ?? $this->created_at;
        return $fecha->format('d/m/Y H:i');
    }

    /**
     * Obtener el nombre del método de pago en español
     */
    public function getMetodoPagoNombreAttribute()
    {
        $metodos = [
            'efectivo' => 'Efectivo',
            'tarjeta' => 'Tarjeta',
            'transferencia' => 'Transferencia',
            'billetera' => 'Billetera Digital',
            'cc' => 'Cuenta Corriente',
            'cuenta_corriente' => 'Cuenta Corriente',
        ];

        return $metodos[$this->metodo_pago] ?? ucfirst($this->metodo_pago);
    }

    /**
     * Obtener el nombre completo del cliente
     */
    public function getNombreClienteAttribute()
    {
        if ($this->cliente) {
            return trim($this->cliente->nombre . ' ' . $this->cliente->apellido);
        }
        return 'Cliente de Contado';
    }

    /**
     * Obtener el nombre del vendedor
     */
    public function getNombreVendedorAttribute()
    {
        return $this->usuario ? $this->usuario->name : 'Sistema';
    }

    /**
     * ¿Podemos reimprimir?
     */
    public function puedeReimprimir()
    {
        return $this->impreso;
    }

    /**
     * Obtener toda la información de la venta en un array
     */
    public function getResumen()
    {
        return [
            'id' => $this->id,
            'numero' => $this->numero,
            'fecha' => $this->fecha_formateada,
            'cliente' => $this->nombre_cliente,
            'vendedor' => $this->nombre_vendedor,
            'subtotal' => '$' . number_format($this->subtotal ?? $this->total, 2),
            'descuento' => '$' . number_format($this->descuento_monto ?? 0, 2),
            'comision' => '$' . number_format($this->comision ?? 0, 2),
            'total' => $this->total_formateado,
            'metodo_pago' => $this->metodo_pago_nombre,
            'estado' => $this->anulada ? 'ANULADA' : strtoupper($this->estado ?? 'activa'),
            'lista_precios' => strtoupper($this->lista_precios ?? 'minorista'),
        ];
    }

    /**
     * Obtener información para el comprobante
     */
    public function getInfoComprobante()
    {
        $info = $this->getResumen();
        
        // Agregar datos según el método de pago
        if ($this->metodo_pago === 'efectivo') {
            $info['monto_recibido'] = '$' . number_format($this->monto_recibido ?? 0, 2);
            $info['vuelto'] = '$' . number_format($this->vuelto ?? 0, 2);
        } 
        elseif ($this->metodo_pago === 'tarjeta') {
            $info['tipo_tarjeta'] = strtoupper($this->tipo_tarjeta ?? 'N/A');
            $info['ultimos_digitos'] = '****' . ($this->ultimos_digitos ?? '0000');
            $info['codigo_autorizacion'] = $this->codigo_autorizacion ?? 'N/A';
        } 
        elseif ($this->metodo_pago === 'transferencia') {
            $info['numero_transferencia'] = $this->numero_transferencia ?? 'N/A';
            $info['banco'] = strtoupper($this->banco ?? 'N/A');
        } 
        elseif (in_array($this->metodo_pago, ['cc', 'cuenta_corriente'])) {
            $info['saldo_anterior'] = '$' . number_format($this->saldo_anterior ?? 0, 2);
            $info['nuevo_saldo'] = '$' . number_format($this->nuevo_saldo ?? 0, 2);
        }
        
        return $info;
    }

    /**
     * Obtener URL pública del comprobante
     */
    public function getUrlComprobantePublico()
    {
        if ($this->token_comprobante) {
            return route('comprobante.publico', $this->token_comprobante);
        }
        return null;
    }
    /**
     * Una venta puede tener múltiples pagos mixtos
     */
    public function pagosMixtos()
    {
        return $this->hasMany(PagoMixto::class, 'venta_id', 'id');
    }
}
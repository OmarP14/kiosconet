<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * =====================================================
 * MODELO DETALLE VENTA - KIOSCONET
 * =====================================================
 * Representa cada producto vendido en una venta
 * Tabla: detalle_venta
 * 
 * Relaciones:
 * - Pertenece a una Venta
 * - Pertenece a un Producto
 * =====================================================
 */
class DetalleVenta extends Model
{
    // ==========================================
    // CONFIGURACIÓN DE LA TABLA
    // ==========================================
    protected $table = 'detalle_venta';
    protected $primaryKey = 'id';
    public $timestamps = true;

    // ==========================================
    // CAMPOS ASIGNABLES EN MASA
    // ==========================================
    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    // ==========================================
    // CASTING DE TIPOS DE DATOS
    // ==========================================
    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Detalle pertenece a una Venta
     */
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id', 'id');
    }

    /**
     * Detalle pertenece a un Producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }

    // ==========================================
    // EVENTOS DEL MODELO
    // ==========================================

    /**
     * Boot del modelo - Eventos automáticos
     */
    protected static function boot()
    {
        parent::boot();

        // Calcular subtotal antes de guardar
        static::saving(function ($detalle) {
            $detalle->subtotal = $detalle->cantidad * $detalle->precio_unitario;
        });
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    /**
     * Obtener subtotal formateado
     */
    public function getSubtotalFormateadoAttribute()
    {
        return '$' . number_format($this->subtotal, 2);
    }

    /**
     * Obtener precio unitario formateado
     */
    public function getPrecioFormateadoAttribute()
    {
        return '$' . number_format($this->precio_unitario, 2);
    }
}
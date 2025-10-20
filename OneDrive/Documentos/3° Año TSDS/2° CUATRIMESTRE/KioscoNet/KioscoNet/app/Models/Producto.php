<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * =====================================================
 * MODELO PRODUCTO - KIOSCONET
 * =====================================================
 * Representa los productos en el sistema
 * Tabla: productos
 * =====================================================
 */
class Producto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'codigo',
        'categoria',
        'proveedor_id',
        'precio_compra',
        'stock',
        'stock_minimo',
        'fecha_vencimiento',
        'imagen',
        'activo',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'fecha_vencimiento' => 'date',
        'activo' => 'boolean',
        'stock' => 'integer',
        'stock_minimo' => 'integer',
    ];

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Producto pertenece a un Proveedor
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    /**
     * Producto tiene muchos detalles de venta
     */
    public function detallesVenta()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    // ==========================================
    // SCOPES (Filtros reutilizables)
    // ==========================================

    /**
     * Filtrar solo productos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Filtrar productos con stock bajo
     */
    public function scopeStockBajo($query)
    {
        return $query->whereRaw('stock <= stock_minimo');
    }

    /**
     * Filtrar productos sin stock
     */
    public function scopeSinStock($query)
    {
        return $query->where('stock', 0);
    }

    /**
     * Buscar productos por término
     */
    public function scopeBuscar($query, $termino)
    {
        if (empty($termino)) {
            return $query;
        }

        return $query->where(function($q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
              ->orWhere('codigo', 'like', "%{$termino}%")
              ->orWhere('categoria', 'like', "%{$termino}%");
        });
    }

    /**
     * Filtrar por categoría
     */
    public function scopeCategoria($query, $categoria)
    {
        if (empty($categoria)) {
            return $query;
        }

        return $query->where('categoria', $categoria);
    }

    /**
     * Filtrar por estado
     */
    public function scopeEstado($query, $activo)
    {
        if ($activo === null || $activo === '') {
            return $query;
        }

        return $query->where('activo', (bool) $activo);
    }

    // ==========================================
    // MÉTODOS AUXILIARES
    // ==========================================

    /**
     * Obtener precio de venta con margen
     * ✅ CORREGIDO: Ahora acepta margen personalizado
     */
    public function getPrecioVenta($margen = 40)
    {
        return $this->precio_compra * (1 + $margen / 100);
    }

    /**
     * Verificar si tiene stock disponible
     */
    public function tieneStock($cantidad = 1)
    {
        return $this->stock >= $cantidad;
    }

    /**
     * Verificar si el stock está bajo
     */
    public function stockBajo()
    {
        return $this->stock > 0 && $this->stock <= $this->stock_minimo;
    }

    /**
     * Verificar si está próximo a vencer
     */
    public function proximoVencimiento($dias = 30)
    {
        if (!$this->fecha_vencimiento) {
            return false;
        }
        
        $diasRestantes = now()->diffInDays($this->fecha_vencimiento, false);
        return $diasRestantes >= 0 && $diasRestantes <= $dias;
    }

    /**
     * Verificar si está vencido
     */
    public function estaVencido()
    {
        if (!$this->fecha_vencimiento) {
            return false;
        }

        return $this->fecha_vencimiento->isPast();
    }

    /**
     * Obtener días hasta vencimiento
     */
    public function diasHastaVencimiento()
    {
        if (!$this->fecha_vencimiento) {
            return null;
        }

        return now()->diffInDays($this->fecha_vencimiento, false);
    }

    // ==========================================
    // ACCESSORS (Atributos calculados)
    // ==========================================

    /**
     * Obtener precio de venta formateado
     */
    public function getPrecioVentaFormateadoAttribute()
    {
        return '$' . number_format($this->getPrecioVenta(), 2);
    }

    /**
     * Obtener precio de compra formateado
     */
    public function getPrecioCompraFormateadoAttribute()
    {
        return '$' . number_format($this->precio_compra, 2);
    }

    /**
     * Obtener valor total en inventario
     */
    public function getValorInventarioAttribute()
    {
        return $this->precio_compra * $this->stock;
    }

    /**
     * Obtener ganancia potencial
     */
    public function getGananciaPotencialAttribute()
    {
        $precioVenta = $this->getPrecioVenta();
        $gananciaUnitaria = $precioVenta - $this->precio_compra;
        return $gananciaUnitaria * $this->stock;
    }

    /**
     * Obtener estado de stock en texto
     */
    public function getEstadoStockAttribute()
    {
        if ($this->stock <= 0) {
            return 'sin_stock';
        } elseif ($this->stock <= $this->stock_minimo) {
            return 'stock_bajo';
        } else {
            return 'con_stock';
        }
    }
}
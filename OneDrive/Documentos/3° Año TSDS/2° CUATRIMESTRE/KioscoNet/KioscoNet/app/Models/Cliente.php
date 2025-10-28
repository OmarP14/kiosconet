<?php
/*
 * ARCHIVO: app/Models/Cliente.php
 * ACTUALIZADO: Con campos para métodos de pago y estructura existente
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Tabla asociada al modelo
     */
    protected $table = 'clientes';

    /**
     * Campos que se pueden asignar masivamente
     */
    protected $fillable = [
    'nombre',
    'apellido',        // ✅ EXISTE EN LA BD
    'email', 
    'telefono',
    'direccion',
    'tipo_cliente',    // ✅ EXISTE EN LA BD
    'saldo_cc',
    'limite_credito',
    'activo',
    ];

    /**
     * Campos que deben ser tratados como fechas
     */
    protected $dates = [
    'created_at',
    'updated_at',
    'deleted_at'
    ];

    /**
     * Campos que deben ser casteados a tipos específicos
     */
    protected $casts = [
    'saldo_cc' => 'decimal:2',
    'limite_credito' => 'decimal:2', 
    'activo' => 'boolean',
    ];

    /**
     * Valores por defecto para campos
     */
    protected $attributes = [
    'saldo_cc' => 0.00,
    'limite_credito' => 0.00,
    'activo' => true,
    'tipo_cliente' => 'minorista'
    ];

    // ========== RELACIONES ==========

    /**
     * Ventas del cliente
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    /**
     * Movimientos de cuenta corriente
     * ❌ DESHABILITADO: La clase MovimientoCuentaCorriente no existe en el sistema
     * TODO: Implementar cuando se cree la tabla y modelo de movimientos
     */
    // public function movimientosCuentaCorriente()
    // {
    //     return $this->hasMany(MovimientoCuentaCorriente::class)->orderBy('created_at', 'desc');
    // }

    // ========== ACCESSORS ==========

    /**
     * Obtener nombre completo formateado
     */
    public function getNombreCompletoAttribute()
    {
        return ucwords(strtolower($this->nombre));
    }

    /**
     * Obtener saldo formateado
     */
    public function getSaldoFormateadoAttribute()
    {
        return '$' . number_format($this->saldo_cc, 2);
    }

    /**
     * Obtener límite de crédito formateado
     */
    public function getLimiteCreditoFormateadoAttribute()
    {
        return '$' . number_format($this->limite_credito, 2);
    }

    /**
    * Obtener nombre completo con apellido
    */
    public function getNombreCompletoConApellidoAttribute()
    {
        $nombreCompleto = ucwords(strtolower($this->nombre));
        
        if ($this->apellido) {
            $nombreCompleto .= ' ' . ucwords(strtolower($this->apellido));
        }
        
        return $nombreCompleto;
    }

    /**
     * Obtener crédito disponible
     */
    public function getCreditoDisponibleAttribute()
    {
        // El saldo negativo es deuda, por eso se suma
        return $this->limite_credito + $this->saldo_cc;
    }

    /**
     * Obtener crédito disponible formateado
     */
    public function getCreditoDisponibleFormateadoAttribute()
    {
        return '$' . number_format($this->getCreditoDisponibleAttribute(), 2);
    }

    /**
     * Verificar si tiene crédito suficiente
     */
    public function tieneCreditoSuficiente($monto)
    {
        return $this->getCreditoDisponibleAttribute() >= $monto;
    }

    // ========== SCOPES ==========

    /**
     * Scope para clientes activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para clientes con saldo en cuenta corriente
     */
    public function scopeConSaldo($query)
    {
        return $query->where('saldo_cc', '!=', 0);
    }

    /**
     * Scope para clientes con deuda
     */
    public function scopeConDeuda($query)
    {
        return $query->where('saldo_cc', '<', 0);
    }

    /**
     * Scope para buscar por nombre o email
     */
    /**
 * ✅ Scope para buscar por nombre, apellido, email o teléfono
 */
    public function scopeBuscar($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('nombre', 'LIKE', "%{$termino}%")
            ->orWhere('apellido', 'LIKE', "%{$termino}%")  // ✅ AGREGADO
            ->orWhere('email', 'LIKE', "%{$termino}%")
            ->orWhere('telefono', 'LIKE', "%{$termino}%");
        });
    }

    // ========== MÉTODOS PERSONALIZADOS ==========

    /**
     * Actualizar saldo de cuenta corriente
     * ADAPTADO: Para tu sistema sin tabla de movimientos
     */
    public function actualizarSaldo($monto, $concepto = null, $tipo = 'venta')
    {
        $saldoAnterior = $this->saldo_cc;
        
        // Para ventas, se resta (aumenta la deuda)
        // Para pagos, se suma (reduce la deuda)
        if ($tipo === 'venta') {
            $this->saldo_cc -= $monto;
        } else {
            $this->saldo_cc += $monto;
        }
        
        $this->save();
        
        // OPCIONAL: Si tienes tabla de movimientos, descomenta esto
        /*
        $this->movimientosCuentaCorriente()->create([
            'tipo' => $tipo,
            'monto' => $monto,
            'saldo_anterior' => $saldoAnterior,
            'saldo_nuevo' => $this->saldo_cc,
            'concepto' => $concepto ?? ucfirst($tipo),
            'fecha' => now()
        ]);
        */
        
        return $this;
    }

    /**
     * NUEVO: Método para descontar saldo (usado en tu VentaController)
     */
    public function descontarSaldo($monto)
    {
        $this->saldo_cc -= $monto;
        $this->save();
        return $this;
    }

    /**
     * NUEVO: Método para agregar saldo (usado en anulaciones)
     */
    public function agregarSaldo($monto)
    {
        $this->saldo_cc += $monto;
        $this->save();
        return $this;
    }

    /**
     * Obtener estado del cliente basado en saldo y límite
     */
    public function getEstadoCuentaAttribute()
    {
        if (!$this->activo) {
            return 'inactivo';
        }
        
        if ($this->saldo_cc >= 0) {
            return 'al_dia';
        }
        
        $disponible = $this->getCreditoDisponibleAttribute();
        
        if ($disponible > 0) {
            return 'con_credito';
        } elseif ($disponible >= -100) {
            return 'limite_excedido';
        } else {
            return 'deuda_alta';
        }
    }

    /**
     * Obtener estadísticas del cliente
     */
    public function getEstadisticas()
    {
        return [
            'total_ventas' => $this->ventas()->count(),
            'monto_total_comprado' => $this->ventas()->sum('total'),
            'promedio_venta' => $this->ventas()->avg('total') ?? 0,
            'ultima_venta' => $this->ventas()->latest()->first()?->created_at,
            'dias_sin_comprar' => $this->ventas()->latest()->first()?->created_at?->diffInDays(now()) ?? 0,
            'estado_cuenta' => $this->getEstadoCuentaAttribute()
        ];
    }

    // ========== VALIDACIONES PERSONALIZADAS ==========

    /**
     * Validar antes de guardar
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($cliente) {
            // Validar email único si se proporciona
            if ($cliente->email) {
                $cliente->email = strtolower($cliente->email);
            }
            
            // Formatear nombre
            $cliente->nombre = ucwords(strtolower($cliente->nombre));
            
            // Validar límite de crédito mínimo
            if ($cliente->limite_credito < 0) {
                throw new \InvalidArgumentException('El límite de crédito no puede ser negativo');
            }
        });
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;

    protected $table = 'caja';

    protected $fillable = [
        'usuario_id',
        'tipo',
        'concepto',
        'descripcion',
        'monto',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    // Scopes
    public function scopeIngresos($query)
    {
        return $query->where('tipo', 'ingreso');
    }

    public function scopeEgresos($query)
    {
        return $query->where('tipo', 'egreso');
    }

    public function scopeHoy($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
    }

    // Métodos auxiliares
    public function esIngreso()
    {
        return $this->tipo === 'ingreso';
    }

    public function esEgreso()
    {
        return $this->tipo === 'egreso';
    }

    public static function saldoActual()
    {
        $ingresos = self::ingresos()->sum('monto');
        $egresos = self::egresos()->sum('monto');
        return $ingresos - $egresos;
    }

    public static function saldoHoy()
    {
        $ingresos = self::ingresos()->hoy()->sum('monto');
        $egresos = self::egresos()->hoy()->sum('monto');
        return $ingresos - $egresos;
    }
    /**
     * Saldo entre fechas
     */
    public static function saldoEntreFechas($fechaInicio, $fechaFin)
    {
        $ingresos = self::ingresos()->entreFechas($fechaInicio, $fechaFin)->sum('monto');
        $egresos = self::egresos()->entreFechas($fechaInicio, $fechaFin)->sum('monto');
        return $ingresos - $egresos;
    }

    /**
     * Obtener el último movimiento
     */
    public static function ultimoMovimiento()
    {
        return self::orderBy('created_at', 'desc')->first();
    }

    /**
     * Contar movimientos de hoy
     */
    public static function movimientosHoy()
    {
        return self::hoy()->count();
    }
}
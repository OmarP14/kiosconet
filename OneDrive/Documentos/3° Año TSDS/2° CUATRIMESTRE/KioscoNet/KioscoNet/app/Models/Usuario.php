<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $table = 'usuarios';
    
    // ⚠️ IMPORTANTE: Especificar que la clave primaria es 'id'
    protected $primaryKey = 'id';
    
    // ⚠️ IMPORTANTE: Especificar que es auto-incremental
    public $incrementing = true;
    
    // ⚠️ IMPORTANTE: Tipo de la clave primaria
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'email',
        'usuario',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // ⚠️ IMPORTANTE: Especificar el campo para autenticación
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    // ⚠️ IMPORTANTE: Especificar el campo de email para login
    public function username()
    {
        return 'email';
    }

    // Relaciones
    // Relaciones
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'usuario_id', 'id');  // ✅ CORREGIDO
    }

    public function movimientosCaja()
    {
        return $this->hasMany(Caja::class, 'usuario_id', 'id');  // ✅ CORREGIDO
    }

    // Scopes
    public function scopeAdministradores($query)
    {
        return $query->where('rol', 'administrador');
    }

    public function scopeVendedores($query)
    {
        return $query->where('rol', 'vendedor');
    }

    // Métodos auxiliares
    public function esAdministrador()
    {
        return $this->rol === 'administrador';
    }

    public function esVendedor()
    {
        return $this->rol === 'vendedor';
    }
}
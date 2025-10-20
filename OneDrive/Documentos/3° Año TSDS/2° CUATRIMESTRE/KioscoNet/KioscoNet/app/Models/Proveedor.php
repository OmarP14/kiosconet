<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'direccion',
    ];

    // Relaciones
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    // Scopes
    public function scopeConProductos($query)
    {
        return $query->has('productos');
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre', 'like', "%{$termino}%")
                    ->orWhere('email', 'like', "%{$termino}%");
    }
}
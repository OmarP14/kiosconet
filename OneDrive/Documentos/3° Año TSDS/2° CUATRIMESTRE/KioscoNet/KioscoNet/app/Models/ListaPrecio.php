<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaPrecio extends Model
{
    use HasFactory;

    protected $table = 'listas_precios';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relaciones
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_precio', 'lista_precio_id', 'producto_id')
                    ->withPivot('precio')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeMinorista($query)
    {
        return $query->where('nombre', 'Minorista');
    }

    public function scopeMayorista($query)
    {
        return $query->where('nombre', 'Mayorista');
    }
}
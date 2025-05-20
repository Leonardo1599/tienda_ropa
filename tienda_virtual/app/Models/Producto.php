<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock', 'imagen', 'categoria'];

    public function carritos()
    {
        return $this->hasMany(Carrito::class);
    }

    public static function categoriasRopa()
    {
        return [
            'Polos',
            'Camisas',
            'Pantalones',
            'Shorts',
            'Vestidos',
            'Faldas',
            'Casacas',
            'Abrigos',
            'Ropa Deportiva',
            'Accesorios',
            'Zapatos',
        ];
    }
}

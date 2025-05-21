<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orden extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'total', 'status', 'metodo_pago', 'comprobante_pago', 'comprobante_validado', 'comentario_admin'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

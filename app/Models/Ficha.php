<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ficha extends Model
{
    protected $fillable = [
        'nombre', 'url', 'publicada',
    ];

    // se instancia luego las relaciones con los objetos segun las tablas
    public function beneficios()
    {
        return $this->hasMany(Beneficio::class, 'id_ficha');
    }
}

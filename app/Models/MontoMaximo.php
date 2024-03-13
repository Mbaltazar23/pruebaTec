<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MontoMaximo extends Model
{
    protected $fillable = [
        'id_beneficio', 'monto_minimo', 'monto_maximo',
    ];
    
    // se instancia luego las relaciones con los objetos segun las tablas
    public function beneficio()
    {
        return $this->belongsTo(Beneficio::class, 'id_beneficio');
    }
}

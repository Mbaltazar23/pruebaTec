<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeneficioEntregado extends Model
{
    protected $fillable = [
        'id_beneficio', 'id_user', 'dv', 'total', 'estado', 'fecha',
    ];
    
    // se instancia luego las relaciones con los objetos segun las tablas
    public function beneficio()
    {
        return $this->belongsTo(Beneficio::class, 'id_beneficio');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}

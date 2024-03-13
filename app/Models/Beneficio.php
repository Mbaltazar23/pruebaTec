<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficio extends Model
{
    protected $fillable = [
        'nombre', 'id_ficha', 'fecha',
    ];
    // se instancia luego las relaciones con los objetos segun las tablas
    public function ficha()
    {
        return $this->belongsTo(Ficha::class, 'id_ficha');
    }

    public function beneficiosEntregados()
    {
        return $this->hasMany(BeneficioEntregado::class, 'id_beneficio');
    }

    public function montoMaximo()
    {
        return $this->hasOne(MontoMaximo::class, 'id_beneficio');
    }
}

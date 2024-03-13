<?php

namespace Database\Seeders;

use App\Models\Beneficio;
use App\Models\BeneficioEntregado;
use App\Models\Ficha;
use App\Models\MontoMaximo;
use App\Models\User;
use Illuminate\Database\Seeder;

class BeneficioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           // Se crear al usuario de ejemplo
        $user = User::create([
            'name' => 'Usuario de Ejemplo',
            'run' => '17672694',
            'email' => 'usuario@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Crear la ficha de ejemplo
        $ficha = Ficha::create([
            'nombre' => 'Ficha de Ejemplo',
            'url' => 'https://ejemplo.com',
            'publicada' => true,
        ]);

        // Crear un beneficio asociado a la ficha
        $beneficio = Beneficio::create([
            'nombre' => 'Beneficio_1',
            'id_ficha' => $ficha->id,
            'fecha' => '2024-01-10',
        ]);

        // Crear un beneficio entregado asociado al beneficio y al usuario
        BeneficioEntregado::create([
            'id_beneficio' => $beneficio->id,
            'id_user' => $user->id,
            'dv' => '6',
            'total' => '20000',
            'estado' => '1',
            'fecha' => '2024-01-10',
        ]);

        // Crear sus montos máximos 
        MontoMaximo::create([
            'id_beneficio' => $beneficio->id,
            'monto_minimo' => '0',
            'monto_maximo' => '10000',
        ]);

        MontoMaximo::create([
            'id_beneficio' => $beneficio->id,
            'monto_minimo' => '0',
            'monto_maximo' => '30000',
        ]);
    }
}

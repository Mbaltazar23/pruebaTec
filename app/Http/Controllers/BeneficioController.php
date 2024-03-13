<?php

namespace App\Http\Controllers;

use App\Models\Beneficio;
use App\Models\BeneficioEntregado;
use App\Models\Ficha;
use App\Models\MontoMaximo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeneficioController extends Controller
{

    public function misBeneficios($rut)
    {
        $user = User::where('run', $rut)->first();

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Incluimos en la consulta las relaciones con beneficioEntregado para obtenerlos según el run
        $beneficiosPorAño = BeneficioEntregado::with(['beneficio.ficha', 'beneficio.montoMaximo'])
            ->whereHas('user', function ($query) use ($rut) {
                $query->where('run', $rut);
            })
            ->select(
                DB::raw('YEAR(fecha) as year'),
                DB::raw('COUNT(*) as num'),
                DB::raw('SUM(total) as montoTotal')
            )
            ->groupBy(DB::raw('YEAR(fecha)'))
            ->get();

        $response = [
            'code' => 200,
            'success' => true,
            'data' => $beneficiosPorAño->map(function ($item) use ($rut) { //con el metodo map hacemos el recorrido de los beneficios obtenidos
                $formattedBeneficios = [];

                // Obtener todos los beneficios entregados para el año actual
                $beneficiosEntregados = BeneficioEntregado::whereHas('user', function ($query) use ($rut) {
                    $query->where('run', $rut);
                })
                    ->whereYear('fecha', $item->year)
                    ->with(['beneficio.ficha', 'beneficio.montoMaximo'])
                    ->get();

                // Se recorre cada beneficio entregado para el año actual
                foreach ($beneficiosEntregados as $beneficioEntregado) {
                    $beneficio = $beneficioEntregado->beneficio;
                    $montoMaximo = $beneficio->montoMaximo;
                    $ficha = $beneficio->ficha;

                    // Se verifica si el beneficio cumple con los criterios especificados
                    if ($montoMaximo && $ficha && $ficha->publicada &&
                        $beneficio->total >= $montoMaximo->monto_minimo &&
                        $beneficio->total <= $montoMaximo->monto_maximo) {
                        //
                        $formattedBeneficios[] = [
                            'id' => $beneficio->id,
                            'nombre' => $beneficio->nombre,
                            'total' => '$' . number_format($beneficio->total, 2),
                            'fecha' => Carbon::parse($beneficio->fecha)->format('d/m/Y'),
                            'mes' => Carbon::parse($beneficio->fecha)->format('F'),
                            'ficha' => [
                                'id' => $ficha->id,
                                'nombre' => $ficha->nombre,
                                'url' => $ficha->url,
                                'publicada' => $ficha->publicada,
                            ],
                            'monto_minimo' => '$' . number_format($montoMaximo->monto_minimo, 2),
                            'monto_maximo' => '$' . number_format($montoMaximo->monto_maximo, 2),
                        ];
                    }
                }

                // Si no se encuentran beneficios que cumplan con los criterios, se agrega un mensaje
                if (empty($formattedBeneficios)) {
                    $formattedBeneficios[] = 'No hay beneficios que cumplan con los criterios especificados.';
                }
                // se retorna el año, junto al numero de beneficios, junto al monto total y sus beneficios
                return [
                    'year' => $item->year,
                    'num' => $item->num,
                    'montoTotal' => '$' . number_format($item->montoTotal, 2),
                    'beneficios' => $formattedBeneficios,
                ];
            }),
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    // Método para insertar un nuevo usuario
    public function insertUsuario(Request $request)
    {
        // valido los campos antes de insertar el usuario con sus datos
        $request->validate([
            'name' => 'required|string',
            'run' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->run = $request->run;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);// realizo la encriptacion del password con bcrypt
        $user->save();

        return response()->json(['message' => 'Usuario creado correctamente'], 201);
    }

    // Método para insertar un nuevo beneficio
    public function insertBeneficio(Request $request)
    {
        // validamos los campos a enviar para insertar el nuevo beneficio
        $request->validate([
            'nombre' => 'required|string',
            'id_ficha' => 'required|integer',
            'total' => 'required|numeric',
        ]);

        $beneficio = new Beneficio();
        $beneficio->nombre = $request->nombre;
        $beneficio->id_ficha = $request->id_ficha;
        $beneficio->fecha = now()->toDateString(); // usamos el metodo now parseado a String
        $beneficio->total = $request->total;
        $beneficio->save();

        return response()->json(['message' => 'Beneficio creado correctamente'], 201);
    }

    // Método para insertar una nueva ficha
    public function insertFicha(Request $request)
    {
        // validamos los campos para la insercion de la ficha
        $request->validate([
            'nombre' => 'required|string',
            'url' => 'required|url',
            'publicada' => 'required|boolean',
        ]);

        $ficha = new Ficha();
        $ficha->nombre = $request->nombre;
        $ficha->url = $request->url;
        $ficha->publicada = $request->publicada;
        $ficha->save();

        return response()->json(['message' => 'Ficha creada correctamente'], 201);
    }

    // Método para insertar un nuevo monto máximo
    public function insertMontoMaximo(Request $request)
    {
        // validamos que cumplan con los requisitos para insertar el monto maximo
        $request->validate([
            'id_beneficio' => 'required|integer',
            'monto_minimo' => 'required|numeric',
            'monto_maximo' => 'required|numeric',
        ]);

        $montoMaximo = new MontoMaximo();
        $montoMaximo->id_beneficio = $request->id_beneficio;
        $montoMaximo->monto_minimo = $request->monto_minimo;
        $montoMaximo->monto_maximo = $request->monto_maximo;
        $montoMaximo->save();

        return response()->json(['message' => 'Monto máximo creado correctamente'], 201);
    }

}

<?php

use App\Http\Controllers\BeneficioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// instanciamos las rutas para realizar su llamado por url's o por herramientas para enviar datos
Route::get('/misbeneficios/{rut}', [BeneficioController::class, 'misBeneficios']);
Route::post('/usuarios', [BeneficioController::class, "insertUsuario"]);
Route::post('/beneficios', [BeneficioController::class, 'insertBeneficio']);
Route::post('/fichas', [BeneficioController::class, 'insertFicha']);
Route::post('/monto-maximo', [BeneficioController::class, 'insertMontoMaximo']);
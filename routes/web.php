<?php

use App\Http\Controllers\BeneficioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// instanciamos las rutas para realizar su llamado por url's o por herramientas para enviar datos
Route::get('/misbeneficios/{rut}', [BeneficioController::class, 'misBeneficios']);
Route::post('/usuarios', [BeneficioController::class, "insertUsuario"]);
Route::post('/beneficios', [BeneficioController::class, 'insertBeneficio']);
Route::post('/fichas', [BeneficioController::class, 'insertFicha']);
Route::post('/monto-maximo', [BeneficioController::class, 'insertMontoMaximo']);
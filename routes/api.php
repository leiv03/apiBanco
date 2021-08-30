<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::apiResource('/usuario', App\Http\Controllers\UsuarioController::class);

Route::post('/evento', [App\Http\Controllers\UsuarioController::class,"eventos"]);
Route::get('/balance', [App\Http\Controllers\UsuarioController::class,"balance"]);
Route::post('/reset', [App\Http\Controllers\UsuarioController::class,"reset"]);
Route::post('/crear', [App\Http\Controllers\UsuarioController::class,"crearUsuario"]);


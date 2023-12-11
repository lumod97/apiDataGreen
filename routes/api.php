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

Route::post('get-users','App\Http\Controllers\ServiciosTransporteController@getServiciosTransporte');
Route::post('insertar_servicios_transporte','App\Http\Controllers\ServiciosTransporteController@getServiciosTransporte');
Route::get('get-logs','App\Http\Controllers\ServiciosTransporteController@getLogs');
Route::post('get-products','App\Http\Controllers\ServiciosTransporteController@getProducts');
Route::post('get-servicios-transporte','App\Http\Controllers\ServiciosTransporteController@getServicios');
Route::post('get-asientos-restantes','App\Http\Controllers\ServiciosTransporteController@getAsientosRestantes');
Route::post('aprobar-servicio-transporte', 'App\Http\Controllers\ServiciosTransporteController@aprobarServicioTransporte');


Route::group(['prefix' => 'tareos'], function () {
    Route::post('insertar_tareos', 'App\Http\Controllers\TareosController@insertarTareos');
});


Route::group(['prefix' => 'alimentos'], function () {
    Route::post('get-alimentos', 'App\Http\Controllers\AlimentosController@getAlimentos');
    Route::post('get-alimento-by-id', 'App\Http\Controllers\AlimentosController@getAlimentoById');
    Route::post('insert-alimento', 'App\Http\Controllers\AlimentosController@insertAlimentos');
    Route::post('update-alimento', 'App\Http\Controllers\AlimentosController@updateAlimentos');
    Route::post('delete-alimento', 'App\Http\Controllers\AlimentosController@deleteAlimento');
    Route::post('delete-selected-alimentos', 'App\Http\Controllers\AlimentosController@deleteSelectedAlimentos');
});

Route::group(['prefix'=>'boletas'], function (){
    Route::post('generar-boletas', 'App\Http\Controllers\BoletasPagoController@generarBoletas');
});

Route::group(['prefix'=>'consumidores'], function (){
    Route::post('insertar-consumidor', 'App\Http\Controllers\consumidoresController@insertConsumidor');
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
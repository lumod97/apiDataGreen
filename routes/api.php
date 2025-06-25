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
Route::get('get-data-compras','App\Http\Controllers\KPIController@getCompras');
Route::get('sync-boletas','App\Http\Controllers\SyncBoleterosController@syncDatos');



Route::get('get-dni-data/{id}','App\Http\Controllers\DNIController@getDataDNI');


Route::get('get-ean14-fijo/{numero_fijo}-{cantidad}','App\Http\Controllers\EAN14GeneratorController@generarCodigoEAN14ConVerificadorFijo');


Route::post('get-users','App\Http\Controllers\ServiciosTransporteController@getServiciosTransporte');
Route::post('insertar_servicios_transporte','App\Http\Controllers\ServiciosTransporteController@getServiciosTransporte');
Route::get('get-logs','App\Http\Controllers\ServiciosTransporteController@getLogs');
Route::post('get-products','App\Http\Controllers\ServiciosTransporteController@getProducts');
Route::post('get-servicios-transporte','App\Http\Controllers\ServiciosTransporteController@getServicios');
Route::post('get-asientos-restantes','App\Http\Controllers\ServiciosTransporteController@getAsientosRestantes');
Route::post('aprobar-servicio-transporte', 'App\Http\Controllers\ServiciosTransporteController@aprobarServicioTransporte');

Route::group(['prefix' => 'minigreen'], function(){
    Route::post('registrar_actualización', 'App\Http\Controllers\MiniGreenController@registrarActualización');
    Route::get('validar_version', 'App\Http\Controllers\MiniGreenController@validarVersion');
    Route::get('download-file', 'App\Http\Controllers\MiniGreenController@devolverRutaActualizacion');
});

Route::group(['prefix' => 'tareos'], function () {
    Route::post('insertar_tareos', 'App\Http\Controllers\TareosController@insertarTareos');
    Route::post('extornar_tareos', 'App\Http\Controllers\TareosController@extornarTareos');
});

Route::group(['prefix' => 'transportes'], function () {
    Route::post('obtener_localidades', 'App\Http\Controllers\ServiciosTransporteController@obtenerLocalidades');
});

Route::group(['prefix' => 'personas'], function () {
    Route::get('obtener_personas_con_observacion_mejorado', 'App\Http\Controllers\PersonasController@obtenerPersonasMejorado');
    Route::get('obtener_personas_mejorado', 'App\Http\Controllers\PersonasController@obtenerPersonasMejorado');
    Route::get('obtener_personas_con_observacion', 'App\Http\Controllers\PersonasController@obtenerPersonasConObservacion');
    Route::post('obtener_data_persona', 'App\Http\Controllers\PersonasController@obtenerDataPersona');
    Route::get('obtener_personas', 'App\Http\Controllers\PersonasController@obtenerPersonas');
    Route::post('insertar_registros_sanitario', 'App\Http\Controllers\PersonasController@insertarRegistrosSanitario');

});

Route::group(['prefix' => 'marcas'], function () {
    Route::post('transferir_marcas', 'App\Http\Controllers\MarcasController@transferirMarcas');
});

Route::group(['prefix' => 'logs'], function () {
    Route::post('transferir_logs', 'App\Http\Controllers\LogsController@transferirLogs');
});

Route::group(['prefix' => 'comedor'], function () {
    Route::post('enviar_data', 'App\Http\Controllers\LogsController@transferirAlmuerzos');
});

Route::group(['prefix' => 'sistemas'], function () {
    Route::group(['prefix' => 'soporte'], function () {
        Route::post('transferir_mantenimientos', 'App\Http\Controllers\Sistemas\MantenimientosController@transferirMantenimientos');
    });

    Route::post('obtener_usuarios', 'App\Http\Controllers\SistemasControler@obtenerUsuarios');
});

Route::post('get_pdf', 'App\Http\Controllers\PdfController@generatePdf');
Route::get('get_pdf_barras', 'App\Http\Controllers\CodigoBarrasController@generarPagina');
Route::post('get_pdf_barras_cu', 'App\Http\Controllers\CodigoBarrasController@generarBarras');
Route::get('get_pdf_test', 'App\Http\Controllers\CodigoBarrasController@generarBarrasTest');

Route::group(['prefix' => 'configuracion'], function () {
    Route::post('obtener_data_inicial', 'App\Http\Controllers\ConfigController@obtenerDataInicial');
    Route::post('registrar_terminal', 'App\Http\Controllers\ConfigController@registrarTerminal');
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

Route::group(['prefix'=>'capacitaciones'], function (){
    Route::get('get_areas', 'App\Http\Controllers\capacitacionesController@getAreas');
    Route::get('get_personas', 'App\Http\Controllers\capacitacionesController@getPersonas');
    Route::get('get_cargos', 'App\Http\Controllers\capacitacionesController@getCargos');
    Route::get('get_tipos_capacitacion', 'App\Http\Controllers\capacitacionesController@getTiposCapacitacion');
    Route::get('get_capacitaciones', 'App\Http\Controllers\capacitacionesController@getCapacitaciones');
});

// Route::get('prueba_endpoint', 'App\Http\Controllers\pruebaController@apiPrueba');
Route::post('prueba_endpoint', 'App\Http\Controllers\pruebaController@apiPrueba');
Route::post('prueba_coordenadas', 'App\Http\Controllers\pruebaController@obtenerCoordenadasRedonda');


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
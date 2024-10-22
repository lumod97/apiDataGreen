<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('formats.pdfCertificadoTrabajo');
    try{
        return view('downloadHome');
    } catch (\Exception $th) {
        return $th;
    }
});

Route::get('/download/{filename}', function ($filename) {
    $filePath = storage_path("app/public/files/{$filename}"); // Ruta específica donde se almacenan los archivos

    if (file_exists($filePath)) {
        return response()->download($filePath);
    } else {
        abort(404, "Archivo no encontrado.");
    }
})->name('download');

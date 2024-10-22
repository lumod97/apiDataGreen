<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class LogsController extends Controller
{
    public function transferirLogs(Request $request)
    {
        try {
            $params = [
                json_encode(['logs' => $request['logs']])
            ];

            $logs = json_decode(json_encode($request['logs'], true)); // Decodifica el JSON a un array asociativo

            $primerElemento = $logs[0]; // Acceder al primer elemento de la lista de logs
            
            $response = DB::statement("EXEC DataGreenMovil..sp_insertar_logs_mobile ?", $params);
            
            // File::append(storage_path('logs/log_chronitos_logs.txt'), PHP_EOL . json_encode($params) . PHP_EOL . $logs[0]->mac . PHP_EOL);

            // File::append(storage_path('logs/log_chronitos_logs.txt'), PHP_EOL . 'ERROR'. $response . PHP_EOL);

            return ['code' => 200, 'response' => $params];
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

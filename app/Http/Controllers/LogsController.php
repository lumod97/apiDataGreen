<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogsController extends Controller
{
    public function transferirLogs(Request $request)
    {
        try {
            $params = [
                json_encode(['logs' => $request['logs']])
            ];
            // $response = DB::statement("EXEC sp_insertar_logs_mobile ?", $params);
            return ['code' => 200, 'response' => $params];
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

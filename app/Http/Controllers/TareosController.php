<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class TareosController extends Controller
{
    public function insertarTareos(Request $request)
    {
        // SETEAMOS LA ZONA HORARIA PARA OBTENER LA FECHA Y HORA CORRECTAS
        date_default_timezone_set("America/Lima");
        // OBTENEMOS LA FECHA Y HORA PARA LA INSERCIÓN DE LOS LOGS
        $currentDate = date("Y-m-d H:i:s");
        try {
            // INSERTAMOS LOS LOGS EN UN ARCHIVO DE TEXTO
            File::append(storage_path('logs/log_tareos.txt'), PHP_EOL.'Momento: '.$currentDate.' ----- parametros: '.stripslashes(json_encode($request['tareos'])) . PHP_EOL);

            // INSERTAMOS LOS LOGS EN LA BASE DE DATOS
            $logParams = [
                $request["mac"],
                $request["user_login"],
                $request["app"],
                "sp_Dgm_Tareos_TransferirTareo_V2",
                $request["parametros"]
            ];
            DB::statement("insert into Datagreen..Logs values(GETDATE(), ?, ?, ?, ?, ?)",$logParams);
     
            // ENVIAMOS LOS TAREOS PARA SU INSERCIÓN
            $params = [
                json_encode(['tareos' => $request['tareos']])
            ];
            $data = DB::select("SET NOCOUNT ON; EXEC DataGreenMovil..sp_Dgm_Tareos_TransferirTareo_V2 ?;", $params);

            // RETORNAMOS EL RESPONSE
            return ['code' => 200, 'response' => $data];

        } catch (\Throwable $th) {
            
            // GUARDAMOS EL ERROR Y EL LOG EN UN ARCHIVO DE TEXTO
            $errorText = strval($th);
            File::append(storage_path('logs/logs_apis.txt'), PHP_EOL. $errorText . PHP_EOL);
            File::append(storage_path('logs/log_tareos.txt'), PHP_EOL.'Momento: '.$currentDate.' ----- parametros: '.stripslashes(json_encode($request['tareos'])) . PHP_EOL);

            // GUARDAMOS EL LOG EN LA BASE DE DATOS
            $logParams = [
                $request["mac"],
                $request["user_login"],
                $request["app"],
                "ERROR sp_Dgm_Tareos_TransferirTareo_V2",
                $request["parametros"]
            ];
            DB::statement("insert into Datagreen..Logs values(GETDATE(), ?, ?, ?, ?, ?)",$logParams);

            // RETORNAMOS EL RESPONSE
            return ['code' => 500, 'response' => strval($th)];
        }
    }

    public function getLogs()
    {
        return DB::select("SELECT TOP 100 * FROM " . env("DATAGREEN") . "..Logs WHERE Parametros LIKE '%000000000EAX%' ORDER BY 1;");
    }
}

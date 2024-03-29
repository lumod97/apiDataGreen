<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class ServiciosTransporteController extends Controller
{
    public function getServiciosTransporte(Request $request)
    {
        //return $request['idServicioTransporte'];
        $idServicioTransporte = $request['idServicioTransporte'];
        $existsServicioTransporte = DB::select("select count(*) response from trx_ServiciosTransporte where Id= '" . $idServicioTransporte . "';")[0];
        if ($existsServicioTransporte->response == 1) {
            $newId = "EXECUTE sp_Dgm_Gen_obtenerNuevoId ?,?,?";
            $params = [
                "trx_ServiciosTransporte",
                $request['idEmpresa'],
                $request['idDispositivo']
            ];
            $nuevoId = DB::select($newId, $params)[0];
            return ['code' => 500, 'newId' => $nuevoId->Detalle, 'response' => strval("AGREGADO CORRECTAMENTE CON ID DIFERENTE")];
        } else {
            $jsonPasajeros = json_encode(['pasajeros' => $request['pasajeros']]);
            $queryUnidad = "INSERT INTO trx_ServiciosTransporte VALUES(" . $request['unidad'] . ")";
            DB::unprepared($queryUnidad);

            // $logParams = [
            //     $request["mac"],
            //     $request["user_login"],
            //     $request["app"],
            //     "sp_Dgm_ServiciosTransporte_TransferirRegistroTransporte",
            //     $request["parametros"]
            // ];
            // DB::statement("insert into Datagreen..Logs values(GETDATE(), ?, ?, ?, ?, ?)",$logParams);

            $result = DB::statement("EXEC sp_Dgm_ServiciosTransporte_TransferirRegistroTransporte ?;", [$jsonPasajeros]);
            return ['code' => 200, 'newId' => "nada mano", 'response' => strval("AGREGADO CORRECTAMENTE")];
        }
        try {
        } catch (\Throwable $th) {
            //throw $th;
            return ['code' => 500, 'response' => strval($th)];
        }
    }

    public function getLogs()
    {
        return DB::select("SELECT TOP 100 * FROM DataGreen..Logs WHERE Parametros LIKE '%000000000EAX%' ORDER BY 1;");
    }
}
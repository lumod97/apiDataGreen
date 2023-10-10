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
        return $request['idServicioTransporte'];
        $idServicioTransporte = $request['idServicioTransporte'];
        $existsServicioTransporte = DB::select("select * from trx_ServiciosTransporte where Id= '" . $idServicioTransporte . "';", []);
        try {
            
            $jsonPasajeros = json_encode(['pasajeros' => $request['pasajeros']]);
            $queryUnidad = "INSERT INTO trx_ServiciosTransporte VALUES(" . $request['unidad'] . ")";
            DB::unprepared($queryUnidad);
            $result = DB::select("EXEC sp_Dgm_ServiciosTransporte_TransferirRegistroTransporte ?;", [$jsonPasajeros]);
            return ['response' => $existsServicioTransporte];
        } catch (\Throwable $th) {
            //throw $th;
            $newId = "EXECUTE sp_Dgm_Gen_obtenerNuevoId ?,?,?";
            $params = [
                "trx_ServiciosTransporte",
                $request['idEmpresa'],
                $request['idDispositivo']
            ];
            $nuevoId = DB::select($newId, $params);
            return ['response' => $existsServicioTransporte];
        }
    }

    public function getLogs(){
        return DB::select("SELECT * FROM Logs WHERE Parametros LIKE '%000000000EAX%' ORDER BY 1;");
    }
}

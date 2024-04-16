<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ServiciosTransporteController extends Controller
{
    public function getServiciosTransporte(Request $request)
    {

        // try {
        // SETEAMOS LA ZONA HORARIA PARA OBTENER LA FECHA Y HORA CORRECTAS
        date_default_timezone_set("America/Lima");
        // OBTENEMOS LA FECHA Y HORA PARA LA INSERCIÓN DE LOS LOGS
        $currentDate = date("Y-m-d H:i:s");

        $idServicioTransporte = $request['idServicioTransporte'];
        $existsServicioTransporte = DB::select("select count(*) response from trx_ServiciosTransporte where Id= '" . $idServicioTransporte . "';")[0];
        if ($existsServicioTransporte->response >= 1) {
            $newId = "EXECUTE DataGreenMovil..sp_Dgm_Gen_obtenerNuevoId ?,?,?";
            $params = [
                "trx_ServiciosTransporte",
                $request['idEmpresa'],
                $request['idDispositivo']
            ];
            $nuevoId = DB::select($newId, $params)[0];
            return ['code' => 500, 'newId' => $nuevoId->Detalle, 'response' => strval("AGREGADO CORRECTAMENTE CON ID DIFERENTE")];
        } else {
            // INSERTAMOS LOS LOGS EN UN ARCHIVO DE TEXTO
            File::append(storage_path('logs/log_transportes.txt'), PHP_EOL . 'Momento: ' . $currentDate . ' ----- parametros: ' . stripslashes(json_encode($request['unidad']) . json_encode(['pasajeros' => $request['pasajeros']]),) . PHP_EOL);

            // INSERTAMOS LOS LOGS EN LA BASE DE DATOS
            $logParams = [
                $request["mac"],
                $request["userLogin"],
                $request["app"],
                "sp_Dgm_ServiciosTransporte_TransferirRegistroTransporte",
                json_encode($request['unidad']) . json_encode(['pasajeros' => $request['pasajeros']]),
            ];

            DB::statement("insert into Datagreen..Logs values(GETDATE(), ?, ?, ?, ?, ?)", $logParams);
            $jsonPasajeros = json_encode(['pasajeros' => $request['pasajeros']]);
            $queryUnidad = "INSERT INTO DataGreenMovil..trx_ServiciosTransporte VALUES(" . $request['unidad'] . ")";
            DB::unprepared($queryUnidad);

            $result = DB::statement("EXEC DataGreenMovil..sp_Dgm_ServiciosTransporte_TransferirRegistroTransporte ?;", [$jsonPasajeros]);
            if ($result == 1) {
                return ['code' => 200, 'newId' => "nada mano", 'response' => strval("AGREGADO CORRECTAMENTE")];
            } else {
                //throw $th;
                // INSERTAMOS LOS LOGS EN UN ARCHIVO DE TEXTO
                File::append(storage_path('logs/log_transportes.txt'), PHP_EOL . 'ERROR: Momento: ' . $currentDate . ' ----- parametros: ' . stripslashes(json_encode($request['unidad']) . json_encode(['pasajeros' => $request['pasajeros']]),) . PHP_EOL);
                $logParams = [
                    $request["mac"],
                    $request["userLogin"],
                    $request["app"],
                    "ERROR sp_Dgm_ServiciosTransporte_TransferirRegistroTransporte",
                    json_encode($request['unidad']) . json_encode(['pasajeros' => $request['pasajeros']]),
                ];

                DB::statement("insert into Datagreen..Logs values(GETDATE(), ?, ?, ?, ?, ?)", $logParams);
                return ['code' => 500, 'newId' => "nada mano", 'response' => strval("Ha ocurrido un error al insertar el registro")];
                // return ['code' => 500, 'response' => strval($th)];
            }
        }
        // } catch (\Throwable $th) {

        // }
    }

    public function getLogs()
    {
        // return DB::select("SELECT TOP 10 * FROM DataGreen..usuarios");
        return DB::select("select top 9
                                st.Id numeroLinea,
                                st.IdVehiculo fruta,
                                st.IdRuta variedad,
                                st.Pasajeros cantidad,
                                st.Fecha hora,
                                -- st.IdConductor usuario,
                                CONCAT_WS(' ', TRIM(p.Paterno), TRIM(p.Materno), TRIM(p.Nombres)) usuario,
                                st.IdEstado estado
                            from DataGreenMovil..trx_ServiciosTransporte st
                            inner join DataGreenMovil..mst_Personas p on p.NroDocumento = st.IdConductor
                            order by Fecha desc");
    }
}

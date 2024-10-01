<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ServiciosTransporteController extends Controller
{
    public function getServiciosTransporte(Request $request)
    {

        try {
            DB::transaction(function () use ($request) {
                // Ejecutar el procedimiento almacenado
                DB::statement('EXEC sp_Dgm_ServiciosTransporte_TransferirRegistroTransporte_Test ?, ?, ?, ?', [
                    json_encode(['pasajeros' => $request->pasajeros]),
                    $request->unidad,
                    $request->mac,
                    $request->usuario
                ]);
            });
            
            // Si todo fue exitoso, devolver respuesta exitosa
            return response()->json([
                'success' => true,
                'message' => 'Registro de transporte transferido con éxito.',
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            // Este bloque captura errores de la base de datos
            return response()->json([
                'success' => false,
                'message' => 'Error al transferir el registro de transporte.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            // Este bloque captura cualquier otro tipo de error
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado al transferir el registro de transporte.',
                'error' => $e->getMessage(),
            ], 500);
        }



        // // try {
        // // SETEAMOS LA ZONA HORARIA PARA OBTENER LA FECHA Y HORA CORRECTAS
        // date_default_timezone_set("America/Lima");
        // // OBTENEMOS LA FECHA Y HORA PARA LA INSERCIÓN DE LOS LOGS
        // $currentDate = date("Y-m-d H:i:s");

        // $idServicioTransporte = $request['idServicioTransporte'];
        // $existsServicioTransporte = DB::select("select count(*) response from DatagreenMovil..trx_ServiciosTransporte where Id= '" . $idServicioTransporte . "';")[0];
        // if ($existsServicioTransporte->response >= 1) {
        //     $newId = "EXECUTE DataGreenMovil..sp_Dgm_Gen_obtenerNuevoId ?,?,?";
        //     $params = [
        //         "trx_ServiciosTransporte",
        //         $request['idEmpresa'],
        //         $request['idDispositivo']
        //     ];
        //     $nuevoId = DB::select($newId, $params)[0];
        //     File::append(storage_path('logs/log_transportes.txt'), PHP_EOL . 'UPDATE ID FROM: ' . $idServicioTransporte . ' TO: ' . $nuevoId->Detalle . PHP_EOL);
        //     return ['code' => 500, 'newId' => $nuevoId->Detalle, 'response' => strval("AGREGADO CORRECTAMENTE CON ID DIFERENTE")];
        // } else {
        //     // INSERTAMOS LOS LOGS EN UN ARCHIVO DE TEXTO
        //     File::append(storage_path('logs/log_transportes.txt'), PHP_EOL . 'Momento: ' . $currentDate . ' ----- parametros: ' . stripslashes(json_encode($request['unidad']) . json_encode(['pasajeros' => $request['pasajeros']]),) . PHP_EOL);

        //     // INSERTAMOS LOS LOGS EN LA BASE DE DATOS
        //     $logParams = [
        //         $request["mac"],
        //         $request["userLogin"],
        //         $request["app"],
        //         "sp_Dgm_ServiciosTransporte_TransferirRegistroTransporte",
        //         json_encode($request['unidad']) . json_encode(['pasajeros' => $request['pasajeros']]),
        //     ];

        //     // DB::statement("insert into Datagreen..Logs values(GETDATE(), ?, ?, ?, ?, ?)", $logParams);
        //     $jsonPasajeros = json_encode(['pasajeros' => $request['pasajeros']]);
        //     // $queryUnidad = "INSERT INTO DataGreenMovil..trx_ServiciosTransporte VALUES(" . $request['unidad'] . ")";
        //     // DB::unprepared($queryUnidad);

        //     $params = [
        //         $jsonPasajeros,
        //         $request['unidad'],
        //         $request['mac'],
        //         $request['usuario']
        //     ];

        //     try {
        //         // $result = DB::statement("EXEC DataGreenMovil..sp_Dgm_ServiciosTransporte_TransferirRegistroTransporte ?, ?;", $params);
        //         $result = DB::statement("EXEC DataGreenMovil..sp_Dgm_ServiciosTransporte_TransferirRegistroTransporte_Test ?, ?, ?, ?;", $params);
        //         return ['code' => 200, 'newId' => "no hay nuevo id", 'response' => strval("AGREGADO CORRECTAMENTE")];
        //     } catch (\Throwable $th) {
        //         //throw $th;
        //         // INSERTAMOS LOS LOGS EN UN ARCHIVO DE TEXTO
        //         File::append(storage_path('logs/log_transportes.txt'), PHP_EOL . 'ERROR: Momento: ' . $currentDate . ' ----- parametros: ' . stripslashes(json_encode($request['unidad']) . json_encode(['pasajeros' => $request['pasajeros']]),) . PHP_EOL);
        //         $logParams = [
        //             $request["mac"],
        //             $request["userLogin"],
        //             $request["app"],
        //             "ERROR sp_Dgm_ServiciosTransporte_TransferirRegistroTransporte",
        //             json_encode($request['unidad']) . json_encode(['pasajeros' => $request['pasajeros']]),
        //         ];

        //         // DB::statement("insert into Datagreen..Logs values(GETDATE(), ?, ?, ?, ?, ?)", $logParams);

        //         // Elimina los espacios en blanco y las comillas simples alrededor de los valores
        //         $unidad = str_replace(["'", " "], "", $request['unidad']);

        //         // Convierte la cadena en un array
        //         $valores = explode(',', $unidad);

        //         // Obtén el segundo valor del array
        //         $segundo_valor = $valores[1];

        //         // Muestra el segundo valor
        //         // echo $segundo_valor;

        //         return ['code' => 500, 'newId' => $segundo_valor, 'response' => strval("Ha ocurrido un error al insertar el registro")];
        //         // return ['code' => 500, 'response' => strval($th)];
        //     }
        // }
        // } catch (\Throwable $th) {

        // }
    }

    public function obtenerLocalidades(Request $request)
    {
        $dataRequest = $request->all();
        // return count($request->all());
        // RECORREMOS EL ARRAY PARA PODER REALIZAR EL PROCESO SERVICIO POR SERVICIO DE ACUERDO A LOS SELECCIONADOS DESDE DATAGRIDVIEW DE DATAGREEN
        foreach ($dataRequest as $servicio) {
            $selectParams = [
                $servicio['id_servicio_transporte']
            ];

            // return $servicio['id_servicio_transporte'];
            // return $selectParams;

            $dbdata = DB::select("select * from DataGreenMovil..trx_ServiciosTransporte_Detalle WHERE IdServicioTransporte = ?", $selectParams);

            # Reemplaza 'YOUR_API_KEY' con tu clave de API de Google Maps
            $client = new \GuzzleHttp\Client([
                'verify' => false,
                'headers' => [
                    'User-Agent' => 'ApiDataGreen/1.0 (luiggigmd.97@gmail.com)',
                    'Referer' => '56.10.3.24:8000'
                ]
            ]);
            $url = 'https://maps.googleapis.com/maps/api/geocode/json';

            foreach ($dbdata as $key => $value) {
                // echo $value;

                if ($value->coordenadas_marca !== '') {
                    $coordinatesArray = explode(',', $value->coordenadas_marca);
                    // PRUEBA DE IMPRESIÓN DE PARÁMETROS
                    $response = $client->get($url, [
                        'query' => [
                            'latlng' => $coordinatesArray[0] . "," . $coordinatesArray[1],
                            'key' => "AIzaSyDp5ZkC71arspYxkBJDrU5WMLrczWW3y2w",
                        ]
                    ]);

                    $data = json_decode($response->getBody(), true);

                    if ($data['status'] == 'OK') {
                        // Procesar la respuesta para encontrar el administrative_area_level_3
                        $location = "sin_localidad";
                        if (!empty($data['results'])) {
                            $i = 0;
                            foreach ($data['results'] as $component) {
                                if (in_array('administrative_area_level_3', $component['address_components'][$i]['types'])) {
                                    $location = $component['address_components'][0]['long_name'];
                                }
                            }
                            $i++;
                        }
                    }
                } else {
                    $location = "sin_coordenadas";
                }

                $updateParams = [
                    $location,
                    $servicio["id_servicio_transporte"],
                    $value->Item
                ];
                // return $updateParams;

                DB::statement("UPDATE DataGreenMovil..trx_ServiciosTransporte_Detalle SET localidad_marca = ? WHERE IdServicioTransporte = ? AND Item = ?", $updateParams);

                // INSERTAMOS LOS LOGS EN LA BASE DE DATOS
                // $logParams = [
                //     $servicio["momento"],
                //     $servicio["mac"],
                //     $servicio["id_usuario"],
                //     $servicio["aplicativo"],
                //     $servicio["descripcion"],
                //     $servicio["id_servicio_transporte"]
                // ];

                // DB::statement("insert into Datagreen..Logs values(?, ?, ?, ?, ?, ?)", $logParams);
            }
        }

        return 'holisuelto';
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

<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class TareosController extends Controller
{
    // public function insertarTareos(Request $request)
    // {


    //     // SETEAMOS LA ZONA HORARIA PARA OBTENER LA FECHA Y HORA CORRECTAS
    //     date_default_timezone_set("America/Lima");
    //     // OBTENEMOS LA FECHA Y HORA PARA LA INSERCIÓN DE LOS LOGS
    //     $currentDate = date("Y-m-d H:i:s");
    //     try {

    //         $params = [
    //             $request['mac'],
    //             $request['imei'],
    //             $request['ultimo_correlativo'],
    //             $request['user_id'],
    //             '00G'
    //         ];

    //         // return $params;
    //         // ACTUALIZAMOS LOS CORRELATIVOS CON LOS DEL MOVIL PARA QUE ASÍ NO NOS DÉ UN CORRELATIVO NUEVO, EN CASO LO REQUIERA, QUE YA EXISTA EN EL MOVIL.
    //         DB::statement("EXEC DataGreenMovil..sp_actualizar_correlativo_desde_movil ?, ?, ?, ?, ?", $params);

    //         // INSERTAMOS LOS LOGS EN UN ARCHIVO DE TEXTO
    //         // File::append(storage_path('logs/log_tareos.txt'), PHP_EOL.'Momento: '.$currentDate.' ----- parametros: '.stripslashes(json_encode($request['tareos'])) . PHP_EOL);

    //         // INSERTAMOS LOS LOGS EN LA BASE DE DATOS
    //         // $logParams = [
    //         //     $request["mac"],
    //         //     $request["user_login"],
    //         //     $request["app"],
    //         //     "sp_Dgm_Tareos_TransferirTareo_V2",
    //         //     $request["parametros"]
    //         // ];
    //         // DB::statement("insert into Datagreen..Logs values(GETDATE(), ?, ?, ?, ?, ?)",$logParams);

    //         // ENVIAMOS LOS TAREOS PARA SU INSERCIÓN
    //         // $params = [
    //         //     json_decode('{"tareos": [{"IdEmpresa":"01","Id":"09K000000009","Fecha":"2024-09-26","IdTurno":"M","IdEstado":"PE","IdUsuarioCrea":"40392384","FechaHoraCreacion":"2024-09-26 13:53:18","IdUsuarioActualiza":"40392384","TotalHoras":"112.75","TotalRendimientos":"2071","TotalDetalles":"14","Observaciones":null,"detalles":[{"IdEmpresa":"01","Idtareo":"09K000000009","Item":"1","Dni":"48966860","IdPlanilla":"PAS","IdConsumidor":"910-P","IdCultivo":null,"IdVariedad":null,"IdActividad":"U2C","IdLabor":"006","SubTotalHoras":"8","SubTotalRendimiento":"175","Observacion":null,"ingreso":"2024-09-26 13:38:42","salida":null},{"IdEmpresa":"01","Idtareo":"09K000000009","Item":"2","Dni":"43087629","IdPlanilla":"PAS","IdConsumidor":"910-P","IdCultivo":null,"IdVariedad":null,"IdActividad":"U2C","IdLabor":"006","SubTotalHoras":"8","SubTotalRendimiento":"176","Observacion":null,"ingreso":"2024-09-26 13:39:50","salida":null},{"IdEmpresa":"01","Idtareo":"09K000000009","Item":"3","Dni":"17453098","IdPlanilla":"PAS","IdConsumidor":"910-P","IdCultivo":null,"IdVariedad":null,"IdActividad":"U2C","IdLabor":"006","SubTotalHoras":"8","SubTotalRendimiento":"173","Observacion":null,"ingreso":"2024-09-26 13:40:37","salida":null},{"IdEmpresa":"01","Idtareo":"09K000000009","Item":"4","Dni":"43270778","IdPlanilla":"PAS","IdConsumidor":"910-P","IdCultivo":null,"IdVariedad":null,"IdActividad":"U2C","IdLabor":"006","SubTotalHoras":"8","SubTotalRendimiento":"173","Observacion":null,"ingreso":"2024-09-26 13:41:12","salida":null},{"IdEmpresa":"01","Idtareo":"09K000000009","Item":"5","Dni":"47815185","IdPlanilla":"PAS","IdConsumidor":"910-P","IdCultivo":null,"IdVariedad":null,"IdActividad":"U2C","IdLabor":"006","SubTotalHoras":"8","SubTotalRendimiento":"142","Observacion":null,"ingreso":"2024-09-26 13:42:11","salida":null},{"IdEmpresa":"01","Idtareo":"09K000000009","Item":"6","Dni":"77235227","IdPlanilla":"PAS","IdConsumidor":"910-P","IdCultivo":null,"IdVariedad":null,"IdActividad":"U2C","IdLabor":"006","SubTotalHoras":"8","SubTotalRendimiento":"142","Observacion":null,"ingreso":"2024-09-26 13:42:30","salida":null},{"IdEmpresa":"01","Idtareo":"09K000000009","Item":"7","Dni":"73579871","IdPlanilla":"PAS","IdConsumidor":"910-P","IdCultivo":null,"IdVariedad":null,"IdActividad":"U2C","IdLabor":"006","SubTotalHoras":"8","SubTotalRendimiento":"150","Observacion":null,"ingreso":"2024-09-26 13:42:56","salida":null},{"IdEmpresa":"01","Idtareo":"09K000000009","Item":"8","Dni":"41027152","IdPlanilla":"PAS","IdConsumidor":"910-P","IdCultivo":null,"IdVariedad":null,"IdActividad":"U2C","IdLabor":"006","SubTotalHoras":"8","SubTotalRendimiento":"150","Observacion":null,"ingreso":"2024-09-26 13:44:41","salida":null},{"IdEmpresa":"01","Idtareo":"09K000000009","Item":"9","Dni":"43503888","IdPlanilla":"PAS","IdConsumidor":"910-P","IdCultivo":null,"IdVariedad":null,"IdActividad":"U2C","IdLabor":"006","SubTotalHoras":"8","SubTotalRendimiento":"141","Observacion":null,"ingreso":"2024-09-26 13:45:10","salida":null},{"IdEmpresa":"01","Idtareo":"09K000000009","Item":"10","Dni":"48690158","IdPlanilla":"PAS","IdConsumidor":"910-P","IdCultivo":null,"IdVariedad":null,"IdActividad":"U2C","IdLabor":"006","SubTotalHoras":"8","SubTotalRendimiento":"141","Observacion":null,"ingreso":"2024-09-26 13:45:25","salida":null},{"IdEmpresa":"01","Idtareo":"09K000000009","Item":"11","Dni":"46867214","IdPlanilla":"PAS","IdConsumidor":"910-P","IdCultivo":null,"IdVariedad":null,"IdActividad":"U2C","IdLabor":"006","SubTotalHoras":"8","SubTotalRendimiento":"147","Observacion":null,"ingreso":"2024-09-26 13:45:45","salida":null},{"IdEmpresa":"01","Idtareo":"09K000000009","Item":"12","Dni":"41568770","IdPlanilla":"PAS","IdConsumidor":"910-P","IdCultivo":null,"IdVariedad":null,"IdActividad":"U2C","IdLabor":"006","SubTotalHoras":"8","SubTotalRendimiento":"148","Observacion":null,"ingreso":"2024-09-26 13:46:03","salida":null},{"IdEmpresa":"01","Idtareo":"09K000000009","Item":"13","Dni":"42044078","IdPlanilla":"PAS","IdConsumidor":"910-P","IdCultivo":null,"IdVariedad":null,"IdActividad":"U2C","IdLabor":"006","SubTotalHoras":"8","SubTotalRendimiento":"213","Observacion":null,"ingreso":"2024-09-26 13:46:34","salida":null},{"IdEmpresa":"01","Idtareo":"09K000000009","Item":"14","Dni":"40392384","IdPlanilla":"PAS","IdConsumidor":"910-P","IdCultivo":null,"IdVariedad":null,"IdActividad":"U2S","IdLabor":"001","SubTotalHoras":"8.75","SubTotalRendimiento":"0","Observacion":null,"ingreso":"2024-09-26 13:48:51","salida":null}],"FechaHoraActualizacion":"2024-09-26 13:53:18","FechaHoraTransferencia":"null","MD5String":"e5c95fb5f970bcaa1ccc147b738659ff"},{"IdEmpresa":"01","Id":"0AX00000000A","Fecha":"2024-09-26","IdTurno":"M","IdEstado":"PE","IdUsuarioCrea":"10645758","FechaHoraCreacion":"2024-09-26 14:11:21","IdUsuarioActualiza":"10645758","TotalHoras":"8.75","TotalRendimientos":"0","TotalDetalles":"1","Observaciones":null,"detalles":[{"IdEmpresa":"01","Idtareo":"0AX00000000A","Item":"1","Dni":"10645758","IdPlanilla":"PAS","IdConsumidor":"704-P","IdCultivo":null,"IdVariedad":null,"IdActividad":"U2S","IdLabor":"001","SubTotalHoras":"8.75","SubTotalRendimiento":"0","Observacion":null,"ingreso":"2024-09-26 14:10:59","salida":null}],"FechaHoraActualizacion":"2024-09-26 14:11:21","FechaHoraTransferencia":null,"MD5String":"aad939b653b1f44cd9d39f4f69ab89e4"}]}'),
    //         //     '0B765C94D843016B',
    //         //     '0B765C94D843016B'
    //         // ];

    //         $params = [
    //             json_encode(['tareos' => $request['tareos']]),
    //             $request["mac"],
    //             $request["imei"]
    //         ];

    //         // return $params[0];
    //         // $params = [
    //         //     json_encode(['tareos' => $request['tareos']]),
    //         //     $request["mac"],
    //         //     $request["imei"]
    //         // ];
    //         $data = DB::select("SET NOCOUNT ON; EXEC DataGreenMovil..sp_Dgm_Tareos_TransferirTareo_V5 ?, ?, ?;", $params);
    //         // throw new Exception('SUAVE MANOOOOOOO');

    //         // return $data;

    //         $response = $data;

    //         // return $data;

    //         // return $response;

    //         // RETORNAMOS EL RESPONSE
    //         if($response->code == '500'){
    //             // File::append(storage_path('logs/log_tareos.txt'), json_encode(['code' => $response->code, 'response' => $data]));
    //             return ['code' => $response->code, 'response' => $data];
    //             // throw new Exception($response);
    //         }else if($response->code == '200'){
    //             // File::append(storage_path('logs/log_tareos.txt'), json_encode(['code' => $response->code, 'response' => $data]));
    //             return ['code' => $response->code, 'response' => $data];
    //         }


    //     } catch (\Throwable $th) {
    //         // return $th;

    //         // GUARDAMOS EL ERROR Y EL LOG EN UN ARCHIVO DE TEXTO
    //         $errorText = strval($th);
    //         // echo 'holaaa';
    //         // File::append(storage_path('logs/logs_apis.txt'), PHP_EOL. $errorText . PHP_EOL);
    //         // File::append(storage_path('logs/log_tareos.txt'), PHP_EOL.'ERROR: Momento: '.$currentDate.' ----- parametros: '.stripslashes(json_encode($request['tareos'])) . PHP_EOL. $errorText);

    //         // GUARDAMOS EL LOG EN LA BASE DE DATOS
    //         // $logParams = [
    //         //     $request["mac"],
    //         //     $request["user_login"],
    //         //     $request["app"],
    //         //     "ERROR sp_Dgm_Tareos_TransferirTareo_V3",
    //         //     $request["parametros"]
    //         // ];
    //         // DB::statement("SET NOCOUNT ON; insert into Datagreen..Logs values(GETDATE(), ?, ?, ?, ?, ?)",$logParams);

    //         // RETORNAMOS EL RESPONSE
    //         return ['code' => 500, 'response' => $errorText];
    //     }
    // }

    public function insertarTareos(Request $request)
    {
        // SETEAMOS LA ZONA HORARIA PARA OBTENER LA FECHA Y HORA CORRECTAS
        date_default_timezone_set("America/Lima");
        // OBTENEMOS LA FECHA Y HORA PARA LA INSERCIÓN DE LOS LOGS
        $currentDate = date("Y-m-d H:i:s");
        try {
            // INSERTAMOS LOS LOGS EN UN ARCHIVO DE TEXTO
            File::append(storage_path('logs/log_tareos.txt'), PHP_EOL . 'Momento: ' . $currentDate . ' ----- parametros: ' . stripslashes(json_encode($request['tareos'])) . PHP_EOL);

            // INSERTAMOS LOS LOGS EN LA BASE DE DATOS
            // $logParams = [
            //     $request["mac"],
            //     $request["user_login"],
            //     $request["app"],
            //     "sp_Dgm_Tareos_TransferirTareo_V2",
            //     $request["parametros"]
            // ];
            // DB::statement("insert into Datagreen..Logs values(GETDATE(), ?, ?, ?, ?, ?)",$logParams);

            // ENVIAMOS LOS TAREOS PARA SU INSERCIÓN
            $params = [
                json_encode(['tareos' => $request['tareos']]),
                $request["mac"],
                $request["imei"]
            ];
            $data = DB::select("SET NOCOUNT ON; EXEC DataGreenMovil..sp_Dgm_Tareos_TransferirTareo_V4 ?, ?, ?;", $params);
            // throw new Exception('SUAVE MANOOOOOOO');

            // return $data;

            $response = $data[0];

            // return $response;

            // RETORNAMOS EL RESPONSE
            if ($response->code == '500') {
                return ['code' => $response->code, 'response' => $data];
                // throw new Exception($response);
            } else if ($response->code == '200') {
                return ['code' => $response->code, 'response' => $data];
            }
        } catch (\Throwable $th) {
            // return $th;

            // GUARDAMOS EL ERROR Y EL LOG EN UN ARCHIVO DE TEXTO
            $errorText = strval($th);
            File::append(storage_path('logs/logs_apis.txt'), PHP_EOL . $errorText . PHP_EOL);
            File::append(storage_path('logs/log_tareos.txt'), PHP_EOL . 'Momento: ' . $currentDate . ' ----- parametros: ' . stripslashes(json_encode($request['tareos'])) . PHP_EOL);

            // GUARDAMOS EL LOG EN LA BASE DE DATOS
            // $logParams = [
            //     $request["mac"],
            //     $request["user_login"],
            //     $request["app"],
            //     "ERROR sp_Dgm_Tareos_TransferirTareo_V3",
            //     $request["parametros"]
            // ];
            // DB::statement("SET NOCOUNT ON; insert into Datagreen..Logs values(GETDATE(), ?, ?, ?, ?, ?)",$logParams);

            // RETORNAMOS EL RESPONSE
            return ['code' => 500, 'response' => $errorText];
        }
    }

    public function extornarTareos(Request $request)
    {
        try {
            // Aquí puedes realizar las operaciones necesarias
            // Por ejemplo, podrías hacer una consulta a la base de datos o algún otro procesamiento

            // Supongamos que la operación se realiza correctamente
            return response()->json([
                'success' => true,
                'message' => 'Tareos extornados con éxito.'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores opcional, pero asegurando una respuesta positiva
            return response()->json([
                'success' => true,  // Siempre devolver éxito
                'message' => 'Tareos extornados, pero se produjo un error: ' . $e->getMessage()
            ], 200);
        }
    }


    public function getLogs()
    {
        return DB::select("SELECT TOP 100 * FROM " . env("DATAGREEN") . "..Logs WHERE Parametros LIKE '%000000000EAX%' ORDER BY 1;");
    }
}

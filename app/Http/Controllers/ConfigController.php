<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class ConfigController extends Controller
{
    public function obtenerDataInicial(Request $request){
        $statementPuertas = "SELECT * FROM Datagreen..Cns_Puertas WHERE IdEstado = 'AC' AND IdTipo = 0;";
        $statementTerminales = "SELECT * FROM Datagreen..Cns_Terminales WHERE IdEstado = 'AC';";
        $statementAcciones = "SELECT * FROM DataGreen..Cns_Acciones";

        $dataPuertas = DB::select($statementPuertas);
        $dataTerminales = DB::select($statementTerminales);
        $dataAcciones = DB::select($statementAcciones);

        $data = [
            "data_puertas" => $dataPuertas,
            "data_terminales" => $dataTerminales,
            "data_acciones" => $dataAcciones
        ];

        return ['code' => 200, 'response' => $data];
    }

    public function registrarTerminal(Request $request){

        // VERIFICAMOS LA EXISTENCIA DEL REGISTRO SIN ID Y CON LOS PARAMETROS ENVIADOS
        $exists = DB::select("SELECT COUNT(*) exist FROM Datagreen..Cns_Terminales WHERE Mac = '".$request['mac']."';")[0]->exist;
        $newId = DB::select("SELECT MAX(Id) + 1 newId FROM Datagreen..Cns_Terminales")[0]->newId;

        if($exists > 0){

            $params = [
                $request['ip'],
                $request['descripcion'],
                $request['id_puerta'],
                $request['mac']
            ];

            $query = "UPDATE Datagreen..Cns_Terminales SET Ip = ?, Descripcion = ?, IdPuerta = ?, FechaActualiza = GETDATE() WHERE Mac = ?";

            File::append(storage_path('logs/log_chronitos.txt'), PHP_EOL . 'ERROR'. PHP_EOL . json_encode($params));

            DB::statement($query, $params);

            return ['code' => 208, 'response' => ['message' => 'El terminal se ha actualizado con éxito en los servidores, por ya existir.', 'deviceId' => $newId]];
        }else{
            // return $newId;
            $params = [
                $newId,
                $request['mac'],
                $request['ip'],
                $request['descripcion'],
                $request['id_puerta'],
                $request['tipo']
            ];
    
            $statement = "INSERT INTO Datagreen..Cns_Terminales VALUES( ?, ?, ?, ?, ?, GETDATE(), GETDATE(), 'AC', ?)";
    
            try {
                DB::insert($statement, $params);
                return ['code' => 200, 'response' => ['message' => 'El terminal se ha registrado con éxito en los servidores.', 'idInserted' => $newId]];
            } catch (\Throwable $th) {
                return ['code' => 500, 'response' => ['message' => $th]];
            }
        }
    }
}

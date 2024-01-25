<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
    public function obtenerDataInicial(Request $request){
        $statementPuertas = "SELECT * FROM Datagreen..Cns_Puertas WHERE IdEstado = 'AC' AND IdTipo = 0;";
        $statementTerminales = "SELECT * FROM DataGreenMovil_TestEnviroment..Cns_Terminales WHERE IdEstado = 'AC';";

        $dataPuertas = DB::select($statementPuertas);
        $dataTerminales = DB::select($statementTerminales);

        $data = [
            "data_puertas" => $dataPuertas,
            "data_terminales" => $dataTerminales
        ];

        return ['code' => 200, 'response' => $data];
    }

    public function registrarTerminal(Request $request){

        // VERIFICAMOS LA EXISTENCIA DEL REGISTRO SIN ID Y CON LOS PARAMETROS ENVIADOS
        $exists = DB::select("SELECT COUNT(*) exist FROM Datagreen..Cns_Terminales WHERE Mac = '".$request['mac']."' and Ip = '".$request['ip']."';")[0]->exist;
        $newId = DB::select("SELECT MAX(Id) + 1 newId FROM Datagreen..Cns_Terminales")[0]->newId;

        if($exists > 0){
            return ['code' => 208, 'response' => ['message' => 'El terminal se ha registrado con éxito en los servidores.', 'deviceId' => $newId]];
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

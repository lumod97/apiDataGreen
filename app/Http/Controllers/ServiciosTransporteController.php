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
        // return $request['idServicioTransporte'];
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
            $result = DB::statement("EXEC sp_Dgm_ServiciosTransporte_TransferirRegistroTransporte ?;", [$jsonPasajeros]);
            return ['code' => 200, 'newId' => "nada mano", 'response' => strval("AGREGADO CORRECTAMENTE")];
        }
        try {
        } catch (\Throwable $th) {
            //throw $th;
            return ['code' => 500, 'response' => strval($th)];
        }
    }

    public function getLogs(Request $request)
    {
		return DB::select("select TOP 20 doc.idproducto, doc.descripcion, doc.cantidad, doc.precio_unitario, doc.precio_unitariodscto, doc.fecha_occliente from AgricolaSanJuan_2020..DORDENCOMPRA doc INNER JOIN AgricolaSanJuan_2020..PRODUCTOS p ON p.IDPRODUCTO = doc.idproducto
        where p.DESCRIPCION like '%".$request['busqueda']."%';", []);
    }

    public function getProducts(Request $request)
    {
		return DB::select("select TOP 100 doc.idproducto, doc.descripcion, doc.cantidad, doc.precio_unitario, doc.precio_unitariodscto, doc.fecha_occliente from AgricolaSanJuan_2020..DORDENCOMPRA doc INNER JOIN AgricolaSanJuan_2020..PRODUCTOS p ON p.IDPRODUCTO = doc.idproducto
        where p.DESCRIPCION like '%".$request['busqueda']."%';", []);
    }

    public function getServicios(Request $request)
    {
        return DB::select('select IdRuta, r.Dex, SUM(Pasajeros) pasajeros from trx_ServiciosTransporte st INNER JOIN mst_Rutas r ON r.Id = st.IdRuta  where fecha BETWEEN CONVERT(date,getdate()) and CONVERT(date,getdate()) group by IdRuta, r.Dex');
    }

    public function getAsientosRestantes(Request $request){
        return DB::select("select st.Id, st.Fecha, st.IdRuta, r.Dex Ruta, v.Id Vehiculo, st.Pasajeros, v.capacidad , SUM(v.Capacidad - st.Pasajeros) AsientosRestantes from trx_ServiciosTransporte st INNER JOIN mst_Rutas r ON r.Id = st.IdRuta INNER JOIN mst_Vehiculos v ON v.Id = st.IdVehiculo where fecha BETWEEN CONVERT(date,getdate()) and CONVERT(date,getdate()) GROUP BY st.Id, st.Fecha, st.IdRuta, r.Dex, st.Pasajeros, v.capacidad, V.Id order by v.Id", []);
    }

    public function aprobarServicioTransporte(Request $request){
        $params = [
            // 'codTransporte',
            // 'usuario'
            $request['idServicio'],
            '72450801'
        ];
        return DB::statement("EXEC DataGreen..sp_Dg_Logistica_Movimientos_ServiciosTransporte_Moviles '".$request['idServicio']."', '72450801'", []);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;


class AlimentosController extends Controller
{
    public function getAlimentos(Request $request)
    {
        $params = [
            $request['perPage'],
            $request['currentPage'],
            $request['filtroTexto'],
            $request['date_from'],
            $request['date_to']
        ];
        $result = DB::select('EXEC DataGreen..sp_obtener_alimentos_paginados ?,?,?,?,?', $params);

        $collection = collect($result);

        $paginator = new Paginator($collection, $request['perPage'], $request['currentPage']);

        // $paginator['cc'] = $cc;

        return $paginator;
    }

    public function getAlimentoById(Request $request)
    {
        $data = DB::select("SELECT r.dni, r.fecha, CASE WHEN pg.NOMBRES IS NOT NULL THEN CONCAT(pg.NOMBRES, ' ',pg.A_PATERNO, ' ', pg.A_MATERNO) ELSE 'Trabajador desconocido' END trabajador FROM DataGreen..Cns_Refrigerios r LEFT join AgricolaSanJuan_2020..PERSONAL_GENERAL pg ON pg.nrodocumento = r.Dni  WHERE Dni = '".$request["dni"]."' AND Fecha = '".$request["fecha"]."'");
        return $data;
    }

    public function insertAlimentos(Request $request)
    {
        $params = [
            $request['dni'],
            $request['fecha']
        ];
        $result = DB::unprepared("INSERT INTO DataGreen..Cns_Refrigerios VALUES('".$request['dni']."', '".$request['fecha']."')",$params);
        return $result;
    }

    public function updateAlimentos(Request $request)
    {
        $result = DB::unprepared("UPDATE DataGreen..Cns_Refrigerios SET Dni = '".$request['dniChange']."' WHERE Dni = '".$request['dniOriginal']."' AND Fecha = '".$request['fecha']."'");
        return $result;
    }

    public function deleteAlimento(Request $request)
    {
        $params = [
            $request['id']
        ];

        $result = DB::statement("DELETE FROM DataGreen..Cns_Refrigerios WHERE Dni=?",$params);
        return $result;
    }

    public function deleteSelectedAlimentos(Request $request)
    {
        $result = [];
        foreach ($request->all() as $key => $value) {
            $result = DB::statement('DELETE from DataGreen..Cns_Refrigerios where Dni=? AND Fecha=?',[$value['Dni'], $value['Fecha']]);
        }
        return $result;
    }
}
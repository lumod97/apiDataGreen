<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class capacitacionesController extends Controller
{
    public function getAreas(){
        $data = DB::select('SELECT TRIM(IDAREA) idarea, TRIM(DESCRIPCION) descripcion FROM AgricolaSanJuan_2020..AREAS WHERE ESTADO = 1');
        return $data;
    }

    public function getPersonas(){
        // $data = DB::select('EXEC DataGreenMovil..sp_descargar_personas_con_area');
        $data = DB::select("SELECT
                                PG.NRODOCUMENTO dni,
                                PG.IDCODIGOGENERAL idcodigogeneral,
                                TRIM(PG.NOMBRES) name,
                                TRIM(PG.A_PATERNO) a_paterno,
                                TRIM(PG.A_MATERNO) a_materno,
                                CONCAT_WS(' ',TRIM(PG.NOMBRES), TRIM(PG.A_PATERNO), TRIM(PG.A_MATERNO)) trabajador,
                                'SJ01' idarea,
                                CASE
                                    WHEN PR.MOTIVO_SALIDA IS NOT NULL THEN PR.MOTIVO_SALIDA
                                    WHEN PG.LISTA_NEGRA = 'SI' THEN 'LISTA NEGRA'
                                    ELSE 'SIN OBSERVACION'
                                END observaciones,
                                IIF(P.ACTIVADO_EN_ESTAPLANI = 1, 'ACTIVO', 'INACTIVO') estado
                            -- INTO #PersonalDetalles
                            FROM AgricolaSanJuan_2020..PERSONAL_GENERAL PG
                                LEFT JOIN AgricolaSanJuan_2020..PERSONALR PR ON PR.NRODOCUMENTO = pg.NRODOCUMENTO AND PR.estado = 1
                                INNER JOIN AgricolaSanJuan_2020..PERSONAL P ON P.IDCODIGOGENERAL = pg.IDCODIGOGENERAL
                                INNER JOIN (SELECT MAX(PZ.FECHA_INICIOPLANILLA) MAX_FECHA_INICIOPLANILLA, PZ.IDCODIGOGENERAL
                                FROM AgricolaSanJuan_2020..PERSONAL PZ
                                GROUP BY PZ.IDCODIGOGENERAL) PS ON PS.IDCODIGOGENERAL = P.IDCODIGOGENERAL AND PS.MAX_FECHA_INICIOPLANILLA = P.FECHA_INICIOPLANILLA
                            WHERE PG.ESTADO = 1;");
        return $data;
    }

    public function getCargos(){
        $data = DB::select('SELECT IDCARGO idcargo, DESCRIPCION descripcion from AgricolaSanJuan_2020..CARGOS_PERSONAL where estado = 1');
        return $data;
    }

    public function getTiposCapacitacion(){
        $data = DB::select('SELECT id, description descripcion FROM DataGreenMovil..sst_tipos_capacitacion');
        return $data;
    }

    public function getCapacitaciones(){
        $data = DB::select('SELECT id, [description] descripcion, tipo, fecha, capacitador_id, horas_estimadas, created_by, created_at, updated_by, updated_at from DataGreenMovil..sst_capacitaciones
        ');
        return $data;
    }
}

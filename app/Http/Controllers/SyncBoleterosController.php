<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncBoleterosController extends Controller
{
    public function syncDatos()

    {
        set_time_limit(3200); // Aumenta el tiempo a 300 segundos (5 minutos)
        try {
            // Ejecutar el stored procedure en SQL Server
            $resultados = DB::select('EXEC Datagreen..SP_GenerarInformacionBoleta_SanJuan');

            $resultadosFechas = DB::select('select [CODIGO] codigo, [DIA] dia, [H NOR] hnor,	[HE 25] he25,	[HE 35] he35,	[H NOC] hnoc,	[HEN25] hen25,	[HEN35] hen35,	[FECHA] fecha from DataGreen..DetalleDiasXaBoleta');

            // return $resultados;
            $mysql1 = DB::connection('mysql_destino_1');
            $mysql2 = DB::connection('mysql_destino_2');
            
            // Truncar usando conexiones ya guardadas
            $mysql1->unprepared("TRUNCATE TABLE bdatospersonal;");
            $mysql2->unprepared("TRUNCATE TABLE bdatospersonal;");
            
            $mysql1->unprepared("TRUNCATE TABLE bdetalleboleta;");
            $mysql2->unprepared("TRUNCATE TABLE bdetalleboleta;");
            
            $mysql1->unprepared("TRUNCATE TABLE bregistrogenerarboleta;");
            $mysql2->unprepared("TRUNCATE TABLE bregistrogenerarboleta;");
            // // Vaciar ambas tablas
            // DB::connection('mysql_destino_1')->unprepared("TRUNCATE TABLE bdatospersonal;");
            // DB::connection('mysql_destino_2')->unprepared("TRUNCATE TABLE bdatospersonal;");
            
            foreach ($resultadosFechas as $fila) {
                    error_log(json_encode($fila));

                $data = [
                    'codigo' => $fila->codigo,
                    'dia' => $fila->dia,
                    'hnor' => $fila->hnor,
                    'he25' => $fila->he25,
                    'he35' => $fila->he35,
                    'hnoc' => $fila->hnoc,
                    'hen25' => $fila->hen25,
                    'hen35' => $fila->hen35,
                    'fecha' => $fila->fecha
                ];

                $mysql1->table('bdetalleboleta')->insert($data);
                $mysql2->table('bdetalleboleta')->insert($data);
            }

            foreach ($resultados as $fila) {
                    error_log(json_encode($fila));

                
                $data = [
                    'codigo'             => $fila->codigo,
                    'apenom'             => $fila->apenom,
                    'basico'             => $fila->basico,
                    'cargo'              => $fila->cargo,
                    'afp'                => $fila->afp,
                    'autogene'           => $fila->autogene,
                    'banco'              => $fila->banco,
                    'cta_banco'          => $fila->cta_banco,
                    'semana'             => $fila->semana,
                    'desde1'             => $fila->desde1,
                    'hasta1'             => $fila->hasta1,
                    'ingr_descri'        => $fila->ingr_descri,
                    'ingr_valor'         => $fila->ingr_valor,
                    'desc_descri'        => $fila->desc_descri,
                    'desc_valor'         => $fila->desc_valor,
                    'apor_descri'        => $fila->apor_descri,
                    'apor_valor'         => $fila->apor_valor,
                    'ingreso'            => $fila->ingreso,
                    'dia1'               => $fila->dia1,
                    'dia2'               => $fila->dia2,
                    'dia3'               => $fila->dia3,
                    'dia4'               => $fila->dia4,
                    'dia5'               => $fila->dia5,
                    'dia6'               => $fila->dia6,
                    'dia7'               => $fila->dia7,
                    'dia8'               => $fila->dia8,
                    'dia9'               => $fila->dia9,
                    'dia10'              => $fila->dia10,
                    'dia11'              => $fila->dia11,
                    'dia12'              => $fila->dia12,
                    'dia13'              => $fila->dia13,
                    'dia14'              => $fila->dia14,
                    'dia15'              => $fila->dia15,
                    'normales'           => $fila->normales,
                    'dobles'             => $fila->dobles,
                    'extras_simples'     => $fila->extras_simples,
                    'ext_dobles'         => $fila->ext_dobles,
                    'total'              => $fila->total,
                    'dias'               => $fila->dias,
                    'dias_nolaborados'   => $fila->dias_nolaborados,
                    'faltas'             => $fila->faltas,
                    'nocturnas'          => $fila->nocturnas,
                    'id'                 => $fila->id
                ];
                
                $mysql1->table('bdatospersonal')->insert($data);
                $mysql2->table('bdatospersonal')->insert($data);
            }
            
            return response()->json(['mensaje' => 'Datos insertados en MySQL correctamente.']);
        } catch (Exception $e) {
            // Registrar el error en el log
            Log::error('Error al sincronizar boletas: ' . $e->getMessage());
            
            // Devolver el error al frontend
            return response()->json([
                'error' => 'Ocurrió un error durante la sincronización.',
                'detalles' => $e->getMessage(), // Puedes eliminar esto en producción
            ], 500);
        }
    }
}

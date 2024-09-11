<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class KPIController extends Controller
{
    public function getCompras(){
        $response = DB::select("EXEC DATAGREENTEST..SP_GET_DATA_COMPRAS");
        $cantVistoBueno = 0;
        $cantPendiente = 0;
        $cantAtendidoTotal = 0;
        $cantRechazado = 0;
        $cantAnulado = 0;
        $cantAtendidoParcial = 0;
        $cantAprobado = 0;

        // V1
        // PE
        // AT
        // CO
        // RE
        // AN
        // TP
        // CE
        // AP
        foreach ($response as $key => $value) {
            switch ($value->ESTADO_PEDIDO) {
                case 'V1':
                    $cantVistoBueno++;
                    break;
                case 'PE':
                    $cantPendiente++;
                    break;
                case 'AT':
                    $cantAtendidoTotal++;
                    break;
                // case 'CO':
                //     $cant++;
                // break;
                case 'RE':
                    $cantRechazado++;
                    break;
                case 'AN':
                    $cantAnulado++;
                    break;
                case 'TP':
                    $cantAtendidoParcial++;
                    break;
                // case 'CE':
                //     $cant++;
                // break;
                case 'AP':
                    $cantAprobado++;
                    break;
            }
        }

        $dataCantidades = [
            "total" => count($response),
            "visto_bueno" => $cantVistoBueno++,
            "pendiente" => $cantPendiente++,
            "atendido_total" => $cantAtendidoTotal++,
            "rechazado" => $cantRechazado++,
            "anulado" => $cantAnulado++,
            "atendido_parcial" => $cantAtendidoParcial++,
            "aprobado" => $cantAprobado++
        ];


        $collection = collect($response);

        $groupedData = $collection->groupBy('COMPRADOR')->map(function ($items, $key) {
            return [
                'COMPRADOR' => $key,
                // 'TOTAL_DOCUMENTOS' => $items->count(),
                'TOTAL_PROYECTOS' => $items->whereNotNull('IDPROYECTO')->count(),
                'TOTAL_PENDIENTES' => $items->where('ESTADO_PEDIDO', 'PE')->count(),
                'TOTAL_VISTO_BUENO' => $items->where('ESTADO_PEDIDO', 'V1')->count(),
                'TOTAL_APROBADOS' => $items->where('ESTADO_PEDIDO', 'AP')->count(),
                'TOTAL_ATENDIDO_TOTAL' => $items->where('ESTADO_PEDIDO', 'AT')->count(),
                'TOTAL_ATENDIDO_PARCIAL' => $items->where('ESTADO_PEDIDO', 'TP')->count(),
                'TOTAL_RECHAZADOS' => $items->where('ESTADO_PEDIDO', 'RE')->count(),
                'TOTAL' => $items->where('ESTADO_PEDIDO')->count(),
                // Agrega aquí más sumas o cálculos que necesites hacer
            ];
        });

        

        // return count($response);
        // case $response[0]->ESTADO:
            
        //     break;
        // return $response[0]->ESTADO;
        // return $dataCantidades;

        return ['code' => 200, 'response' => ['cantidades' => $dataCantidades, 'datos_pedidos'=>$response, 'datos_tabla'=>$groupedData]];
    }
}

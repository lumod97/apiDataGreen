<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoletasPagoController extends Controller
{
    public function generarBoletas(Request $request)
    {

        $xmlEMP = "<?xml version = '1.0' encoding='Windows-1252' standalone='yes'?><VFPData><tmpsubplanilla><codigo>02</codigo><descripcion>EMPLEADOS</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>10</codigo><descripcion>FIJOS_OPERARIOS</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>26</codigo><descripcion>MENSUAL</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>16</codigo><descripcion>OBR-BAMBAMARCA</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>22</codigo><descripcion>OBR-CHEPEN</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>13</codigo><descripcion>OBR-CHOTA</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>07</codigo><descripcion>OBR-CORTE</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>03</codigo><descripcion>OBREROS</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>25</codigo><descripcion>OBR-FERREÑAFE</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>20</codigo><descripcion>OBR-HUAMBOS</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>05</codigo><descripcion>OBR-ICA</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>21</codigo><descripcion>OBR-ICA II</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>24</codigo><descripcion>OBR-ICA III</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>14</codigo><descripcion>OBR-INCAHUASI</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>06</codigo><descripcion>OBR-IQUITOS</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>19</codigo><descripcion>OBR-LLAMA</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>15</codigo><descripcion>OBR-MORROPE</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>23</codigo><descripcion>OBR-OLMOS</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>17</codigo><descripcion>OBR-RIOJA</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>11</codigo><descripcion>OBR-SAN MARTIN</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>12</codigo><descripcion>OBR-SAN MARTIN II</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>18</codigo><descripcion>OBR-STA CRUZ</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>09</codigo><descripcion>PLANTA</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>01</codigo><descripcion>PRACTICANTES</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>27</codigo><descripcion>QUINCENAL</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>04</codigo><descripcion>TALLER</descripcion><elegido>1</elegido></tmpsubplanilla><tmpsubplanilla><codigo>08</codigo><descripcion>VIGILANTES</descripcion><elegido>1</elegido></tmpsubplanilla></VFPData>";
        $semanaDesde = $request['semanaDesde'] != '' ? $request['semanaDesde']: '';
        $semanaHasta = $request['semanaHasta'] != '' ? $request['semanaHasta']: '';

        if($request['planilla'] !='PAS' && $semanaDesde == '' && $semanaHasta == ''){
            return 'que fue papi, no seas abusivo p';
        }

        $statement = "insert into DataGreen..PlanBoletasPagoProv exec AgricolaSanJuan_2020..plan_boletas_pago_prov ?, ?, 'N', ? , '', '', ?, ?, ?, ?, 0, 0, 0, 0, '202310', ''";

        $xmlParams = $xmlEMP;

        $params = [
            $request['empresa'],
            $request['planilla'],
            $xmlParams,
            $request['periodoDesde'],
            $semanaDesde,
            $request['periodoHasta'],
            $semanaHasta
        ];

        return DB::statement($statement, $params);
    }
}

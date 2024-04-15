<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mikehaertl\pdftk\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfController extends Controller
{
    public function generatePdf(Request $request)
    {
        try {
            // return $request;
            // return $request['movimientos'];
            $filesString = "";
            if ($request['template'] === 'FORMATO_DE_VACACIONES') {
                $query = "exec Datagreen..sp_obtenerVacacionesParaFormatoPDF 'vista_normal', '***', '', '', '" . json_encode($request['movimientos']) . "'";
                $data = DB::select($query);
            } elseif ($request['template'] === 'FORMATO_COMPENSACION_HORAS_EXTRA') {
                $query = "exec Datagreen..sp_obtenerCompensacionHorasExtraParaFormatoPDF 'vista_normal', '***', '', '', '" . json_encode($request['movimientos']) . "'";
                $data = DB::select($query);
                // return $data;
            } elseif ($request['template'] === 'plantilla_fotochecks_EDITABLE') {
                $data = [
                    [
                        'nombres' => 'LUIGGI GIUSSEPPI',
                        'apellidos' => 'MORETTI DIOSES',
                        'dni' => '7  2  4  5  0  8  0  1',
                        'codigo_general' => '72450801',
                        'barra' => 'ASJ72450801',
                        'cargo' => 'ANALISTA PROGRAMADOR',
                        'mensaje' => 'Ante cualquier queja o sugerencia comunícate con la línea ética San Juan',
                        'numero' => '942084516'
                    ],
                    [
                        'nombres' => 'CHRISTIAN AYRTON',
                        'apellidos' => 'PACHERRES MENESES',
                        'dni' => '4  6  4  1  6  2  6  4',
                        'codigo_general' => '46416264',
                        'barra' => 'ASJ46416264',
                        'cargo' => 'TÉCNICO DE SOPORTE',
                        'mensaje' => 'Ante cualquier queja o sugerencia comunícate con la línea ética San Juan',
                        'numero' => '942084516'
                    ]
                ];
                // return $data[0]['codigo_general'];
                // return $data;
            } elseif ($request['template'] === 'FORMATO_DE_CERTIFICADO_DE_TRABAJO') {

                $datos = [
                    'nombres' => 'CESPEDES TENORIO MARIA FRAXILA',
                    'dni' => '72450801',
                    'cargo' => 'OBRERO',
                    'diaD' => '17',
                    'mesD' => 'MAYO',
                    'anioD' => '2023',
                    'diaH' => '01',
                    'mesH' => 'MARZO',
                    'anioH' => '2024',
                ];

                // Renderizar la vista a HTML
                $html = view('formats.pdfCertificadoTrabajo', $datos)->render();
                $options = new Options();

                $dompdf = new Dompdf();
                $options = new Options();
                $dompdf->loadHtml($html);
                $options->isPhpEnabled(true);
                $dompdf->render();
                $options->setFontDir(public_path('fonts'));
                $options->setFontCache(public_path('fonts'));
                $options->isFontSubsettingEnabled(true);
                $options->setDefaultFont('Times-Roman');
                $options->setChroot(public_path('font'));
                $dompdf->setOptions($options);
                $dompdf->setPaper('A4', 'landscape');


                // Cargar el HTML en Dompdf
                // $dompdf->setOptions($options);

                // Renderizar el PDF

                // Descargar el PDF generado
                return $dompdf->stream('ejemplo.pdf');
                // Crear una instancia de Dompdf con las opciones configuradas

            } elseif ($request['template'] === 'FORMATO_BOLETAS') {
                $params = [
                    $request['vista'],
                    json_encode($request['objCodigos']),
                    $request['idPlanilla'],
                    'N',
                    $request['codigoDesde'],
                    $request['codigoHasta'],
                    $request['periodoDesde'],
                    $request['semanaDesde'] ? $request['semanaDesde'] : '',
                    $request['periodoHasta'],
                    $request['semanaHasta'] ? $request['semanaHasta'] : '',
                ];
                $dataBoletas = DB::select("exec DataGreen..sp_getDataDiasBoleta ?, ?, ?, ?, ?, ?, ?, ?, ?, ?", $params);
                // return  'holi';
                // return $dataBoletas;
                
                // return $params;
                if (count($dataBoletas) === 0) {
                    return "No existen registros para este trabajador.";
                }

                // TIENE SU PROPIA LÓGICA
                $template_file_route = trim('pdf_formats\\' . $request['template'] . '.pdf');
                $output = trim('raw\\' . $request['template'] . '_#' . '.pdf');
                $url = url('/');
                $params = [];
                $route = 'mixPdf.pdf';
                // SI SON VARIOS, QUE LOS AGRUPE, SI NO, QUE DEVUELVA LA RUTA INDIVIDUAL
                // DATOS PARA DIAS
                if (count($dataBoletas) > 1) {
                    for ($i = 0; $i < count($dataBoletas); $i++) {
                        $pdf = new Pdf($template_file_route);
                        foreach ($dataBoletas[$i] as $key => $value) {
                            $params[$key] = $value;
                        }

                        $save_file_route = str_replace('#', trim($i . $dataBoletas[$i]->codigo_general), $output);

                        $result = $pdf->fillForm($params)->needAppearances()->saveAs(public_path($save_file_route));

                        $filesString .=  public_path($save_file_route) . ' ';
                        // return $filesString;

                        $route = '\\raw\\mixPdf.pdf';
                    }

                    $command = 'pdftk ' . $filesString . ' cat output ' . public_path('raw\\mixPdf.pdf');
                    exec($command);
                } else if (count($dataBoletas) == 1) {
                    foreach ($dataBoletas[0] as $key => $value) {
                        // return $key;
                        // DECODIFICAMOS LA CANTIDAD DE DÍAS
                        // DECODIFICAMOS LOS JSON PARA DEVOLVERLOS CON NOMBRE DE COLUMNA SEGÚN EL PDF
                        $montosConceptos =  json_decode($value);
                        if ($montosConceptos !== null && isset($montosConceptos[0]->descripcion)) {
                            $total = 0;
                            for ($j = 0; $j < count($montosConceptos); $j++) {
                                $params[$key . 'Descripcion.' . $j] = $montosConceptos[$j]->descripcion;
                                $params[$key . 'Valor.' . $j] = str_replace(',','',number_format($montosConceptos[$j]->valor, 2));
                                // echo number_format($montosConceptos[$j]->valor, 2).' ';
                                $total += str_replace(',','',number_format($montosConceptos[$j]->valor, 2));
                            }
                            $params[$key . 'Total'] = str_replace(',','',number_format($total, 2));
                        } else {
                            if ($value == '.00') {
                                $params[$key] = ' - ';
                            } else {
                                $params[$key] = $value;
                            }
                        }
                    }
                    // SACAMOS LOS DIAS ENTRE LAS FECHAS INGRESADAS

                    $dias = json_decode($params['dias']);
                    if ($dias !== null) {
                        for ($i = 0; $i < count(json_decode($params['dias'])); $i++) {
                            $params['vdia' . ($i + 1)] = json_decode($params['dias'])[$i]->vdia;
                            // echo $montosConceptos[$j]->descripcion.PHP_EOL;
                        }
                    }

                    // CALCULAMOS NETO A PAGAR
                    $netoPagar = $params['ingresosTotal'] - $params['retencionesTotal'];
                    $params['netoPagar'] = number_format($netoPagar, 2);
                    // return $params;

                    // return $dataBoletas[0]->codigo_general;
                    $pdf = new Pdf($template_file_route);
                    $save_file_route = str_replace('#', str_replace(' ', '', trim($dataBoletas[0]->codigo_general.$request['fecha_desde'].'_'.$request['fecha_hasta'])), $output);
                    $route = '\\' . $save_file_route;
                    $result = $pdf->fillForm($params)->needAppearances()->saveAs(public_path($save_file_route));
                }
                // DATOS PARA INGRESOS Y PERSONAL
                if ($result == false) {
                    return response(['error' => 'No se ha generado el pdf', 'code' => 500]);
                }

                return  $url . $route;
            }

            if ($request['modo'] == 'vista') {
                return response()->json($data)->header('Content-Type', 'application/json; charset=UTF-8');
            } else {
                if (isset($data[0]->message) && $data[0]->message == 'No contiene registros') {
                    return 'El trabajador no cuenta con movimientos de vacaciones.';
                }

                $template_file_route = trim('pdf_formats\\' . $request['template'] . '.pdf');
                $output = trim('raw\\' . $request['template'] . '_#' . '.pdf');
                // $save_file_route = $request['output'];

                if (!isset($template_file_route)) {
                    return response(['error' => 'No se ha especificado una plantilla', 'code' => 500]);
                } else {
                    $url = url('/');
                    $params = [];
                    $route = 'mixPdf.pdf';
                    // SI SON VARIOS, QUE LOS AGRUPE, SI NO, QUE DEVUELVA LA RUTA INDIVIDUAL
                    if (count($data) > 1) {
                        for ($i = 0; $i < count($data); $i++) {
                            $pdf = new Pdf($template_file_route);
                            foreach ($data[$i] as $key => $value) {
                                $params[$key] = $value;
                            }

                            // $save_file_route = str_replace('#', trim($i . $data[$i]->codigo_general), $output);
                            $save_file_route = str_replace('#', trim($i . $data[$i]['codigo_general']), $output);

                            $result = $pdf->fillForm($params)->needAppearances()->saveAs(public_path($save_file_route));

                            $filesString .=  public_path($save_file_route) . ' ';
                            // return $filesString;

                            $route = '\\raw\\mixPdf.pdf';
                        }

                        $command = 'pdftk ' . $filesString . ' cat output ' . public_path('raw\\mixPdf.pdf');
                        exec($command);
                    } else if (count($data) == 1) {
                        foreach ($data[0] as $key => $value) {
                            if (gettype($value) == 'array') {
                                for ($j = 0; $j < count($value); $j++) {
                                    $params[$key . '.' . $j] = $value[$j];
                                }
                            } else {
                                if ($value == '.00') {
                                    $params[$key] = ' - ';
                                } else {
                                    $params[$key] = $value;
                                }
                            }
                        }
                        // return $params;
                        // return $data[0]->codigo_general;
                        $pdf = new Pdf($template_file_route);
                        // $save_file_route = str_replace('#', trim($data[0]->codigo_general.$params['vdia1'].'_'.$params['vdia15']), $output);
                        $save_file_route = str_replace('#', trim($data[0]['codigo_general']), $output);
                        $route = '\\' . $save_file_route;
                        $result = $pdf->fillForm($params)->needAppearances()->saveAs(public_path($save_file_route));
                    }
                    if ($result == false) {
                        return response(['error' => 'No se ha generado el pdf', 'code' => 500]);
                    }
                    // echo $command;
                    // return $command;
                }

                return  $url . $route;
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }
}

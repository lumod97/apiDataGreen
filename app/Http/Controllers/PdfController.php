<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mikehaertl\pdftk\Pdf;
use Dompdf\Dompdf;
use Dompdf\Exception as DompdfException;
use Dompdf\Options;
use Exception;

use FontLib\Font;

class PdfController extends Controller
{
    public function generatePdf(Request $request)
    {
        try {
            // return $request;
        // return $request['movimientos'];
        $filesString = "";
        if ($request['template'] === 'FORMATO_DE_VACACIONES') {
            $query = "exec Datagreen..sp_obtenerVacacionesParaFormatoPDF 'vista_normal', '***', '', '', '".json_encode($request['movimientos'])."'";
            $data = DB::select($query);
        } elseif ($request['template'] === 'FORMATO_COMPENSACION_HORAS_EXTRA') {
            $query = "exec Datagreen..sp_obtenerCompensacionHorasExtraParaFormatoPDF 'vista_normal', '***', '', '', '".json_encode($request['movimientos'])."'";
            $data = DB::select($query);
            // return $data;
        }elseif($request['template'] === 'FORMATO_DE_CERTIFICADO_DE_TRABAJO') {
            
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
                $dompdf->render();
                $options->setFontDir(public_path('fonts'));
                $options->setFontCache(public_path('fonts'));
                $options->isPhpEnabled(true);
                $options->isFontSubsettingEnabled(true);
                $options->setDefaultFont('DejaVuSansMono');
                $options->setChroot(public_path('font'));
                $dompdf->setOptions($options);
                $dompdf->setPaper('A4', 'landscape');

                
                // Cargar el HTML en Dompdf
                // $dompdf->setOptions($options);
        
                // Renderizar el PDF
        
                // Descargar el PDF generado
                return $dompdf->stream('ejemplo.pdf');
            // Crear una instancia de Dompdf con las opciones configuradas

        }

        if ($request['modo'] == 'vista') {
            return response()->json($data)->header('Content-Type', 'application/json; charset=UTF-8');
        } else {
            if (isset($data[0]->message) && $data[0]->message == 'No contiene registros') {
                return 'El trabajador no cuenta con movimientos de vacaciones.';
            }

            $template_file_route = trim('pdf_formats\\'.$request['template'].'.pdf');
            $output = trim('raw\\'.$request['template'].'_#'.'.pdf');
            // $save_file_route = $request['output'];

            if (!isset($template_file_route)) {
                return response(['error' => 'No se ha especificado una plantilla', 'code' => 500]);
            } else {
                $url = url('/');
                $params = [];
                $route = 'mixPdf.pdf';
                // SI SON VARIOS, QUE LOS AGRUPE, SI NO, QUE DEVUELVA LA RUTA INDIVIDUAL
                if(count($data) > 1){
                    for ($i = 0; $i < count($data); $i++) {
                        $pdf = new Pdf($template_file_route);
                        foreach ($data[$i] as $key => $value) {
                            $params[$key] = $value;
                        }
                        
                        $save_file_route = str_replace('#', trim($i . $data[$i]->codigo_general), $output);
                        
                        $result = $pdf->fillForm($params)->needAppearances()->saveAs(public_path($save_file_route));
                        
                        $filesString .=  public_path($save_file_route).' ';
                        // return $filesString;

                        $route = '\\raw\\mixPdf.pdf';
                        
                    }
                    
                    $command = 'pdftk '.$filesString.' cat output '.public_path('raw\\mixPdf.pdf');
                    exec($command);
                    
                }else if(count($data) == 1){
                    foreach ($data[0] as $key => $value) {
                        $params[$key] = $value;
                    }
                    $pdf = new Pdf($template_file_route);
                    $save_file_route = str_replace('#', trim($data[0]->codigo_general), $output);
                    $route = '\\'.$save_file_route;
                    $result = $pdf->fillForm($params)->needAppearances()->saveAs(public_path($save_file_route));
                }
                if ($result == false) {
                    return response(['error' => 'No se ha generado el pdf', 'code' => 500]);
                }
                // echo $command;
                // return $command;
            }

            return  $url.$route;

            }
        } catch (\Throwable $th) {
            return $th;
        }
    }
}

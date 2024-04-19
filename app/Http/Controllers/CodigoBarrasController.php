<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use mikehaertl\pdftk\Pdf as PDFTK;
use Com\Tecnick\Barcode\Barcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Imagick;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;


class CodigoBarrasController extends Controller
{

    public function generarBarrasTest(Request $request)
    {

        $params = [
            'vista_pdf',
            '[{"codigo": "72450801","encriptado": "11245465"}]',
            '',
        ];

        // return json_encode($request['codigos']);

        $data = DB::select("exec Datagreen..sp_obtener_data_fotocheck ?, ?, ?", $params);
        // return $data;

        $template_file_route = trim('pdf_formats\\plantilla_fotochecks_EDITABLE.pdf');

        $url = url('/');
        for ($i = 0; $i < count($data); $i++) {
            $params = [];
            foreach ($data[$i] as $key => $value) {
                $code = $data[$i]->codigo_general;
                if ($key == 'encriptado') {
                    // Crear una instancia de la biblioteca de código de barras
                    $barcode = new Barcode();

                    // Generar un código de barras de tipo Code 128
                    $barcodeObject = $barcode->getBarcodeObj('C128', $value, -4, 110, 'black', array(325, -12, 0, -12));

                    // Obtener la imagen PNG del código de barras
                    $barcodePNG = $barcodeObject->getPngData();

                    // Convertir la imagen PNG a base64
                    $barcodeBase64 = base64_encode($barcodePNG);
                    // return $barcodeBase64;

                    // Ruta temporal para guardar la imagen PNG
                    // $imageFilePath = 'raw\\barcodeTEST_' . $code . '.png';

                    // Guarda la imagen PNG del código de barras en un archivo temporal
                    // file_put_contents($imageFilePath, $barcodePNG);
                    $params[$key] = $barcodeBase64;
                } else {
                    $params[$key] = $value;
                }
            }
        }
        return view('formats.pdfTest', $params);

        // return $params;
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        // Crear una instancia de mPDF
        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path('/fonts'),
            ]),
            'fontdata' => $fontData + [ // lowercase letters only in font key
                'montserrat' => [
                    'R' => 'Montserrat.ttf',
                ]
            ],
            'default_font' => 'montserrat',
            'format' => [60, 91.8],
            'orientation' => 'P', // Orientación vertical (P)
        ]);

        // Renderizar el archivo Blade con los parámetros
        $html = view('formats.pdfTest', $params)->render();

        // Cargar el HTML en mPDF
        $mpdf->WriteHTML($html);

        // Generar y enviar el PDF al navegador
        $mpdf->Output('archivo.pdf', 'I');
    }

    public function generarBarras(Request $request)
    {
        set_time_limit(111200); // Establece el tiempo máximo de ejecución a 120 segundos
        try {
            $images = [];
            $codigos = [];
            $params = [
                $request['vista'],
                json_encode($request['codigos']),
                $request['idplanilla'],
            ];

            // return json_encode($request['codigos']);

            $data = DB::select("exec Datagreen..sp_obtener_data_fotocheck ?, ?, ?", $params);
            // return $data;

            $template_file_route = trim('pdf_formats\\plantilla_fotochecks_EDITABLE.pdf');

            $url = url('/');
            for ($i = 0; $i < count($data); $i++) {
                $outputDir = '/raw' . '/fotocheck_' . $data[$i]->codigo_general . '.png';


                if (!file_exists($outputDir)) {

                    $params = [];
                    foreach ($data[$i] as $key => $value) {
                        $code = $data[$i]->codigo_general;
                        if ($key == 'encriptado') {
                            // Crear una instancia de la biblioteca de código de barras
                            $barcode = new Barcode();

                            // Generar un código de barras de tipo Code 128
                            $barcodeObject = $barcode->getBarcodeObj('C128', $value, -4, -70, 'black', array(-2, -2, -2, -2));

                            // Obtener la imagen PNG del código de barras
                            $barcodePNG = $barcodeObject->getPngData();

                            // Ruta temporal para guardar la imagen PNG
                            $imageFilePath = 'raw\\barcode_' . $code . '.png';

                            // Guarda la imagen PNG del código de barras en un archivo temporal
                            file_put_contents($imageFilePath, $barcodePNG);
                            $params[$key] = $imageFilePath;
                        } else {
                            $params[$key] = $value;
                        }
                    }

                    // return $params;


                    $dompdf = Pdf::loadView('formats.pdfFotocheck', $params)->setPaper('a4', 'portrait');

                    $dompdf->getOptions()->setIsRemoteEnabled(true);
                    $dompdf->getOptions()->isPhpEnabled(true);
                    $dompdf->getOptions()->setDpi(200);
                    $dompdf->render();
                    $pdfContent = $dompdf->output();

                    file_put_contents('raw\\back.pdf', $pdfContent);

                    $output = trim('raw\\plantilla_fotochecks_EDITABLE_#' . '.pdf');
                    $outputPrev = trim('raw\\plantilla_fotochecks_EDITABLE_#_prev' . '.pdf');

                    $pdf = new PDFTK($template_file_route);
                    $save_file_route_prev = str_replace('#', trim($data[$i]->codigo_general), $outputPrev);
                    $save_file_route = str_replace('#', trim($data[$i]->codigo_general), $output);
                    $result = $pdf->fillForm($params)->needAppearances()->saveAs(public_path($save_file_route_prev));

                    $command = "pdftk {$save_file_route_prev} background raw\\back.pdf output {$save_file_route} compress";
                    exec($command, $output, $return_var);

                    // Crea un objeto Imagick
                    $imagick = new ImagicK();

                    $imagick->setResolution(300, 300);

                    $imagick->readImage($save_file_route);

                    $imagick->setImageFormat('png');

                    $outputDir = '/raw' . '/fotocheck_' . $data[$i]->codigo_general . '.png';
                    if (!file_exists($outputDir)) {
                        mkdir($outputDir, 0755, true);
                    }
                    // Genera un nombre de archivo para la imagen JPG
                    $outputFileName = public_path($outputDir);

                    // Guarda la imagen JPG
                    $imagick->writeImage($outputFileName);

                    // Destruye el objeto Imagick
                    $imagick->destroy();

                    unlink($save_file_route_prev);
                    unlink('raw\\back.pdf');
                    unlink($save_file_route);
                    unlink($imageFilePath);
                    // unlink(public_path($outputDir));
                    // unlink($outputFileName);
                    $images[$i]['ruta'] = base64_encode(file_get_contents(public_path($outputDir)));

                    $codigos[$i] = $data[$i]->codigo_general;
                } else {
                    $outputDir = '/raw' . '/fotocheck_' . $data[$i]->codigo_general . '.png';

                    $images[$i]['ruta'] = base64_encode(file_get_contents(public_path($outputDir)));
                    $codigos[$i] = $data[$i]->codigo_general;
                }
            }


            $datos = [
                'images' => $images
            ];

            $dompdf = Pdf::loadView('formats.pdfCodigosBarras', $datos)->setPaper('a4', 'portrait');
            $dompdf->getOptions()->setIsRemoteEnabled(true);
            $dompdf->getOptions()->isPhpEnabled(true);
            $dompdf->getOptions()->setDpi(200);
            $dompdf->render();

            $pdfContentGenerated = $dompdf->output();
            $outputFolder = 'raw\\generados.pdf';

            file_put_contents($outputFolder, $pdfContentGenerated);
            // return $outputFolder;

            // Genera y transmite el PDF
            // return $dompdf->stream('FotochecksGenerados.pdf');
            // return $codigos;
            $usuario = isset($request['usuario_genera']) ? $request['usuario_genera'] : '72450801';
            // return $usuario;
            $query = "EXEC DataGreen..sp_insertar_registro_fotocheck_entregado '" . json_encode($codigos) . "', " . $usuario . "";
            // return $query;
            DB::statement($query);
            return $url . '/' . $outputFolder;
        } catch (\Exception $th) {
            return $th;
        }
    }

    public function generarPagina()
    {
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
            'images' => [
                ['ruta' => 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('pdf_formats/fotocheck.jpg'))),],
            ],
        ];

        // Renderizar la vista a HTML
        // $html = view('formats.pdfCodigosBarras', $datos)->render();

        // $dompdf = Pdf::loadView('formats.pdfCodigosBarras', $datos)->setPaper('a4', 'portrait');
        $dompdf = Pdf::loadView('formats.pdfFotocheck', $datos)->setPaper('a4', 'portrait');
        // $dompdf->loadHtml($html);
        $options = $dompdf->getOptions();

        $dompdf->getOptions()->setIsRemoteEnabled(true);
        $dompdf->getOptions()->isPhpEnabled(true);
        $dompdf->getOptions()->setDpi(200);
        $dompdf->render();

        // Genera y transmite el PDF
        return $dompdf->stream('ejemplo.pdf');
    }
}

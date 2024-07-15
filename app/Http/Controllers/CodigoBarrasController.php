<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use mikehaertl\pdftk\Pdf as PDFTK;
use Com\Tecnick\Barcode\Barcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Spatie\PdfToImage\Pdf as PdfToImg;
use ZipArchive;

class CodigoBarrasController extends Controller
{

    public function generarBarrasTest(Request $request)
    {
        // // ESTE TEST SE HA REALIZADO PARA INSERTAR LAS IMÁGENES DIRECTAMENTE A LA BASE DE NISIRA SOLO COPIANDO LOS ARCHIVOS EN LA CARPETA => 
        // // // TEST DE ARCHIVOS
        // // $directorio = public_path('pdf_formats/images/fotos_personal');
        // $directorio = 'X:/CNISIRA/FOTOS_T';
        // $archivos = File::glob($directorio . '/*.png');
        // // return $archivos;

        // foreach ($archivos as $archivo) {
        //     $dniActualizar = str_replace('-01', '', str_replace('.png', '', basename($archivo)));
        //     $dniArchivo = str_replace('.png', '', basename($archivo));
        //     $ruta = str_replace(" ", "", "X:\CNISIRA\FOTOS_T\ " . $dniArchivo . ".png");
        //     $query = "UPDATE AgricolaSanJuan_2020..PERSONAL_GENERAL SET PERSONAL_FOTO = '" . $ruta . "' WHERE NRODOCUMENTO = '" . $dniActualizar . "'";
        //     DB::statement($query);
        //     echo $query . '<br>';
        //     // -----------------------------------------------------------
        // }
        // return "fino filipino " . count($archivos);
        // // FIN TEST DE ARCHIVOS


        $images = [];
        // DEFINIMOS UN ARRAY CON LOS ID DE CADA TRABAJADOR
        $codigos = [
            // ["codigo" => "72450801", "encriptado" => "2316805075"],
            // ["codigo" => "47333583", "encriptado" => "6763171566"],
            // ["codigo" => "E3217", "encriptado" => "0238042766"],
            // ["codigo" => "D0086", "encriptado" => "6454361635"],
            ["codigo" => "48749870", "encriptado" => "3653915375"],
            // ["codigo" => "44363337", "encriptado" => "1747063735"],
            // ["codigo" => "72172511", "encriptado" => "3938332566"],

        ];
        // // // DEFINIMOS LOS PARÁMETROS QUE ENVIAREMOS AL SP
        // $params = [
        //     $request['vista'],
        //     // json_encode($request['codigos']),
        //     // CODIFICAMOS A JSON EL ARRAY DE CÓDIGOS, YA QUE EL SP ESPERA UN JSON
        //     // json_encode($request['codigos']),
        //     json_encode($request['codigos']),
        //     $request['idplanilla'],
        // ];

        $params = [
            'vista_pdf_emp',
            // json_encode($request['codigos']),
            // CODIFICAMOS A JSON EL ARRAY DE CÓDIGOS, YA QUE EL SP ESPERA UN JSON
            json_encode($codigos),
            'FMG',
        ];

        // return json_encode($request['codigos']);

        // CAPTURAMOS LA DATA QUE ENVIAMOS PARA PODER GENERAR LOS FOTOCHECKS DE LOS TRABAJADORES SELECCIONADOS
        $data = DB::select("exec Datagreen..sp_obtener_data_fotocheck ?, ?, ?", $params);
        // return $data;

        // SETEAMOS EL TIEMPO LÍMITE PARA LA EJECUÓN A 20S PARA DEBUG, EN PRODUCCIÓN CAMBIAMOS POR LA GENERACIÓN EN MASA
        set_time_limit(200); // Establece el tiempo máximo de ejecución a 200 segundos
        // DEFINIMOS UN ARRAY CON 2 VALORES, ESTOS VALORES CONTENDRÁN LOS LADOS DEL FOTOCHECK PARA GENERARLO DINÁMICAMENTE
        $caras = [
            "Front",
            "Back",
        ];

        // INICIAMOS LA ANIDACIÓN DE 2 FOREACH
        foreach ($data as $keyT => $trabajador) {
            $trabajadorArray = [
                "nombre" => $trabajador->nombres,
                "codigo" => $trabajador->codigo_general,
                "dni" => $trabajador->dni,
                "cargo" => $trabajador->cargo,
                "foto" => $trabajador->foto,
                "codigo_barras" => ''
            ];
            // return $trabajador->foto));
            // $pdfContent = null;
            //  INICIAMOS PROCESO DE GENERACIÓN DE FOTOCHECKS POR CADA TRABAJADOR
            // Ruta a la carpeta donde deseas almacenar los archivos de fotochecks
            $fotochecksFolder = public_path('/fotochecks');

            // Verifica si la carpeta temporal existe, si no, créala
            if (!file_exists($fotochecksFolder)) {
                mkdir($fotochecksFolder, 0777, true); // 0777 otorga permisos de lectura, escritura y ejecución
            }

            foreach ($caras as $key => $value) {
                $code = $trabajador->codigo_general;
                if (isset($trabajador->encriptado) && $value == 'Back') {
                    // Crear una instancia de la biblioteca de código de barras
                    $barcode = new Barcode();

                    // Generar un código de barras de tipo Code 128
                    $barcodeObject = $barcode->getBarcodeObj('C128', $trabajador->encriptado, -4, -70, 'black', array(0, 0, 0, 0));

                    // Obtener la imagen PNG del código de barras
                    $barcodePNG = $barcodeObject->getPngData();

                    // Ruta temporal para guardar la imagen PNG
                    $imageFilePath = $fotochecksFolder . '\\barcode_' . $code . '.png';

                    // Guarda la imagen PNG del código de barras en un archivo temporal
                    file_put_contents($imageFilePath, $barcodePNG);
                    $trabajadorArray['codigo_barras'] = $imageFilePath;
                    // return $trabajadorArray;
                } else {
                    $params[$key] = $value;
                }
                // RENDERIZAMOS LA VISTA FRONTAL
                // INSTANCIAMOS UN PDF BASANDONOS EN DOMPDF Y CARGAMOS LA VISTA EN BLADE
                $pdfContent = PDF::loadView('formats.pdfFotocheckEMP' . $value, $trabajadorArray);
                // PONEMOS LA RUTA DEL PDF, OSEA, DONDE LO VAMOS A GUARDAR
                $pdfPath = $fotochecksFolder . '\\f_' . $trabajador->codigo_general . '_' . $value . '.pdf';
                // GUARDAMOS EL ARCHIVO
                $pdfContent->save($pdfPath);

                // AHORA CONVERTIREMOS EL PDF A PNG COMO LO SOLICITA ZEBRA
                // Ruta donde deseas guardar la imagen PNG resultante
                $imagePath = $fotochecksFolder . '\\f_' . $trabajador->codigo_general . '_' . $value . '.png';
                array_push($images, $imagePath);

                // Crea una instancia de Pdf
                $pdf = new PdfToImg($pdfPath);

                // Ajustamos la resolución de la imagen a generar 
                $pdf->setResolution(1080);

                // Convierte la primera página del PDF en una imagen PNG
                $pdf->setPage(1)->saveImage($imagePath);

                // BORRAMOS EL PDF PARA LIBERAR RECURSOS EN DISCO
                unlink($pdfPath);
                if ($trabajadorArray['codigo_barras'] != '') {
                    unlink($trabajadorArray['codigo_barras']);
                }
            }
        }
        $zip = new ZipArchive;
        $zipDir = public_path('raw');
        $zipFileName = $zipDir . DIRECTORY_SEPARATOR . 'imagenes_generadas.zip';

        // Asegúrate de que el directorio exista
        if (!file_exists($zipDir)) {
            mkdir($zipDir, 0755, true); // Crea el directorio con permisos adecuados
            return response()->json(['message' => 'Que fue mano'], 500);
        }

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            return response()->json(['message' => 'No se pudo crear el archivo ZIP'], 500);
        }
        // Agregar las imágenes al archivo ZIP
        foreach ($images as $imagePath) {
            $imageName = basename($imagePath);
            $zip->addFile($imagePath, $imageName);
        }
        // Cerrar el archivo ZIP
        $zip->close();
        // Agregar las imágenes al archivo ZIP
        foreach ($images as $imagePath) {
            unlink($imagePath);
        }
        return response()->download($zipFileName)->deleteFileAfterSend(false);
    }

    public function generarBarras(Request $request)
    {
        // $zipFileName = 'example.zip';
        // $zipFilePath = public_path('raw'.DIRECTORY_SEPARATOR.$zipFileName);
        // return response()->download($zipFilePath)->deleteFileAfterSend(false);
        // // // // ESTO SE PONE SOLO PARA PRUEBAS, SE ENVIA DIRECTAMENTE UN ARCHIVO PARA PODER REALIZAR LA IMPRESIÓN RÁPIDA
        // set_time_limit(111200); // Establece el tiempo máximo de ejecución a 120 segundos
        set_time_limit(1200000); // Establece el tiempo máximo de ejecución a 120 segundos

        try {
            $images = [];
            $codigos = [];
            $params = [
                $request['vista'],
                json_encode($request['codigos']),
                $request['idplanilla'],
            ];

            $data = DB::select("exec Datagreen..sp_obtener_data_fotocheck ?, ?, ?", $params);
            // return $data;

            $template_file_route = trim('pdf_formats\\plantilla_fotochecks_EDITABLE.pdf');

            $url = url('/');
            for ($i = 0; $i < count($data); $i++) {
                $outputDir = '/raw' . '/fotocheck_' . $data[$i]->codigo_general . '.png';

                $params = [];
                foreach ($data[$i] as $key => $value) {
                    $code = $data[$i]->codigo_general;
                    if ($key == 'encriptado') {
                        // Crear una instancia de la biblioteca de código de barras
                        $barcode = new Barcode();
                        // Generar un código de barras de tipo Code 128
                        $barcodeObject = $barcode->getBarcodeObj('C128', $value, -4, -40, 'black', array(-2, -2, -2, -2));

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

                $dompdf = Pdf::loadView('formats.pdfFotocheck', $params)->setPaper('a4', 'landscape');

                $dompdf->getOptions()->setIsRemoteEnabled(true);
                $dompdf->getOptions()->isPhpEnabled(true);
                $dompdf->getOptions()->setDpi(200);
                $dompdf->render();
                $pdfContent = $dompdf->output();

                file_put_contents('raw\\back.pdf', $pdfContent);

                $output = trim('raw/plantilla_fotochecks_EDITABLE_#' . '.pdf');
                $outputPrev = trim('raw\\plantilla_fotochecks_EDITABLE_#_prev' . '.pdf');

                $pdf = new PDFTK($template_file_route);
                $save_file_route_prev = str_replace('#', trim($data[$i]->codigo_general), $outputPrev);
                $save_file_route = str_replace('#', trim($data[$i]->codigo_general), $output);
                $result = $pdf->fillForm($params)->needAppearances()->saveAs(public_path($save_file_route_prev));

                // PONER EL ARCHIVO BACK DE BACKGROUND AL PDF
                $command = "pdftk {$save_file_route_prev} background raw\\back.pdf output {$save_file_route} compress";
                exec($command, $output, $return_var);
                // // Comando para rotar el PDF 90 grados
                $save_file_route_rotated = $save_file_route . ".pdf";
                $rotate_command = "pdftk {$save_file_route} cat 1-endwest output {$save_file_route_rotated}";
                exec($rotate_command, $output, $return_var);
                // Ruta a la carpeta donde deseas almacenar los archivos temporales dentro de tu aplicación web
                $tempFolder = public_path('/temp');
                // Crea una instancia de Pdf
                $pdf = new PdfToImg(public_path($save_file_route_rotated));
                // Ajustamos la resolución de la imagen a generar 
                $pdf->setResolution(1080);
                // Convierte la primera página del PDF en una imagen PNG
                $outputDir = '/raw' . '/fotocheck_' . $data[$i]->codigo_general . '.jpg';
                // $pdf->setPage(1)->saveImage(public_path($outputDir));
                if ($pdf->setPage(1)->saveImage(public_path($outputDir))) {
                    $images[$i]['ruta'] = public_path($outputDir);
                    $images[$i]['file_name'] = 'fotocheck_'.$data[$i]->codigo_general . '.jpg';
                }
            }

            // GENERAR ZIP PARA ENVIAR A DATAGREEN Y PROCESAR IMPRESIONES
            // Ruta donde se guardará el archivo ZIP
            $zipFileName = 'example.zip';
            $zipFilePath = public_path('raw'.DIRECTORY_SEPARATOR.$zipFileName);
            // ESTO ES SOLO PARA EL DEBUG

            $zip = new ZipArchive;
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                // Añade archivos al ZIP
                foreach ($images as $file) {
                    $zip->addFile($file['ruta'], $file['file_name']);
                }
                // Cierra el ZIP
                $zip->close();
            }
            // unlink($save_file_route_prev);
            // unlink('raw\\back.pdf');
            // unlink($save_file_route);
            // unlink($imageFilePath);
            // unlink($save_file_route_rotated);
            // unlink(public_path($outputDir));
            
            return response()->download($zipFilePath)->deleteFileAfterSend(false);
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
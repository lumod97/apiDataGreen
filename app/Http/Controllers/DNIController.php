<?php

namespace App\Http\Controllers;

use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DNIController extends Controller
{
    public function getDataDNI(Request $request)
    {
        // URL del formulario
        $url = "http://app3.sis.gob.pe/SisConsultaEnLinea/Consulta/frmConsultaEnLinea.aspx";

        // Datos del formulario (reemplaza los campos y valores según sea necesario)
        $data = [
            '__EVENTTARGET' => '',
            '__EVENTARGUMENT' => '',
            '__VIEWSTATE' => '/wEPDwUKLTgxNzQ0Nzc1NmQYAQUPQ2FwdGNoYUNvbnRyb2wxDwUkMjc3NGNlMDUtYTQ0MC00OTNiLThjM2MtOTM3MmM0OTBlZjBlZIqxfWryhlYRph6fhJWGqc663NPJXF2nF0OLaOdGdK+q',
            '__EVENTVALIDATION' => '/wEWDwK1+7nhCQK81oaXAgKY4sHZDAKU4o3aDAKV4o3aDAKwoOzhCwKnscqhCwKHkryACwKajvi6BQKBxNfMAQKNxJvPAQKPxJvPAQLHgbrgDgKVq7KvCALxm8umBTZwspZXewSPO00ZLG1ZYzumgQw/Qg+r4O1CSU3fuzpv',
            'hdnTipo' => '2',
            'cboTipoBusqueda' => '2',
            'txtApePaterno' => '',
            'txtApeMaterno' => '',
            'txtPriNombre' => '',
            'txtSegNombre' => '',
            'cboTipoDocumento' => '1',
            // 'txtNroDocumento' => '16711551',
            'txtNroDocumento' => $request->id,
            'CaptchaControl1' => null, 
            'btnConsultar' => 'Consultar',
        ];

        // Enviar la solicitud POST
        $response = Http::asForm()->post($url, $data);

        // Verifica si la respuesta fue exitosa
        if ($response->successful()) {
            $htmlResponse = $response->body(); // Obtener el cuerpo de la respuesta

            // Crear un nuevo DOMDocument y cargar el HTML
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true); // Ignorar errores de análisis
            $dom->loadHTML($htmlResponse);
            libxml_clear_errors();


            // Buscar la tabla con id "dgConsulta"
            $table = $dom->getElementById('dgConsulta');

            // Crear un nuevo DOMXPath
            $xpath = new DOMXPath($dom);

            $firstTR = $xpath->query("//table[@id='dgConsulta']/tr");

            $persona = [

            ];
            $dni = '';
            $nombres = '';
            $apellido_paterno = '';
            $apellido_materno = '';

            // Verifica si se encontró el primer <tr>
            if ($firstTR->length > 0) {
                $rowData = []; // Array para almacenar los datos de la fila
                $cells = $firstTR->item(1)->childNodes; // Obtener las celdas del <tr>

                // Iterar sobre las celdas y extraer el texto

                $persona = [
                    'dni' => $cells[4]->nodeValue,
                    'nombres' => $cells[7]->nodeValue,
                    'apellido_paterno' => $cells[5]->nodeValue,
                    'apellido_materno' => $cells[6]->nodeValue,
                ];
                return $persona;
            }
        } else {
            // Manejo de errores
            return response()->json(['error' => 'Error al enviar el formulario'], $response->status());
        }
    }
}

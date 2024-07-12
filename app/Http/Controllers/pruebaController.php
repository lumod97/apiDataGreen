<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Geocoder\Geocoder;

class pruebaController extends Controller
{
    public function apiPrueba(Request $request)
    {
        // OBTENEMOS LA DATA PARA PODER REALIZAR LA INSERCIÓN DE LA LOCALIDAD AL MOMENTO DE CONVERTIR DE COORDENADAS DESDE MAPS GEOLOCALITATION API
        // $dbdata = DB::select("SELECT * from DataGreenMovil..ServiciosTransporteDetalleTest WHERE IdServicioTransporte = '03700000006R' and Item = '6'");
        $dbdata = DB::select("SELECT * from DataGreenMovil..ServiciosTransporteDetalleTest WHERE IdServicioTransporte = '03700000006R'");

        # Reemplaza 'YOUR_API_KEY' con tu clave de API de Google Maps
        $client = new \GuzzleHttp\Client([
            'verify' => false,
            'headers' => [
                'User-Agent' => 'ApiDataGreen/1.0 (luiggigmd.97@gmail.com)',
                'Referer' => '56.10.3.24:8000'
            ]
        ]);
        $url = 'https://maps.googleapis.com/maps/api/geocode/json';

        foreach ($dbdata as $key => $value) {
            // echo $value;

            if ($value->coordenadas_marca !== '') {
                $coordinatesArray = explode(',', $value->coordenadas_marca);
                // PRUEBA DE IMPRESIÓN DE PARÁMETROS
                $response = $client->get($url, [
                    'query' => [
                        'latlng' => $coordinatesArray[0] . "," . $coordinatesArray[1],
                        'key' => "AIzaSyDp5ZkC71arspYxkBJDrU5WMLrczWW3y2w",
                    ]
                ]);

                $data = json_decode($response->getBody(), true);

                if ($data['status'] == 'OK') {

                    // Procesar la respuesta para encontrar el administrative_area_level_3
                    $adminAreaLevel3 = "sin_localidad";
                    if (!empty($data['results'])) {
                        $i = 0;
                        // for($i = 0; $i < sizeof($data['results']) ; $i++){
                        // foreach ($data['results'][$i]['address_components'] as $component) {
                        foreach ($data['results'] as $component) {
                            // return $component['address_components'][$i]['types'];
                            // if (in_array('administrative_area_level_3', $component['types'])) {
                            if (in_array('administrative_area_level_3', $component['address_components'][$i]['types'])) {
                                // echo json_encode().'<br>';
                                // $adminAreaLevel3 = $component['long_name'];
                                $adminAreaLevel3 = $component['address_components'][0]['long_name'];
                                // break;
                                // echo 'holi';
                            }
                        }
                        // }
                        $i++;
                    }
                }
            } else {
                $adminAreaLevel3 = "sin_coordenadas";
            }
            
            
            DB::unprepared("UPDATE DataGreenMovil..ServiciosTransporteDetalleTest SET localidad_marca = '".$adminAreaLevel3."' WHERE IdServicioTransporte = '03700000006R' AND Item = '".$value->Item."'");
            // return $adminAreaLevel3;
            // return $data;
            // return $data['results'][0]['address_components'][2]['long_name'];
            // echo $data['results'][0]['address_components'][2]['short_name'] . '<br>';
            echo $adminAreaLevel3 . '<br>';
            // return $data['results'][0]['formatted_address'];
            // } else {
            //     return null;
            // }
            // END PRUEBA DE IMPRESIÓN DE PARÁMETROS
        }

        return "";



        # Reemplaza 'YOUR_API_KEY' con tu clave de API de Google Maps
        $client = new \GuzzleHttp\Client([
            'verify' => false,
            'headers' => [
                'User-Agent' => 'ApiDataGreen/1.0 (luiggigmd.97@gmail.com)',
                'Referer' => '56.10.3.24:8000'
            ]
        ]);
        $url = 'https://maps.googleapis.com/maps/api/geocode/json';
        $response = $client->get($url, [
            'query' => [
                'latlng' => "-6.656740952350361, -79.42873030689088",
                'key' => "AIzaSyDp5ZkC71arspYxkBJDrU5WMLrczWW3y2w",
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if ($data['status'] == 'OK') {
            return $data;
            return $data['results'][0]['address_components'][2]['long_name'];
            return $data['results'][0]['formatted_address'];
        } else {
            return null;
        }




        // if (!isset($request['id'])) {
        //     return ['code' => 500, 'mensaje' => 'El id no existe'];
        // }
        // if (!isset($request['description'])) {
        //     return ['code' => 500, 'mensaje' => 'La description no existe'];
        // }
        // if (!isset($request['usuario'])) {
        //     return ['code' => 500, 'mensaje' => 'El usuario no existe'];
        // }

        // return ['code' => 200, 'mensaje' => 'Proceso exitoso'];

        $client = new \GuzzleHttp\Client([
            'verify' => false,
            'headers' => [
                'User-Agent' => 'ApiDataGreen/1.0 (luiggigmd.97@gmail.com)',
                'Referer' => '56.10.3.24:8000'
            ]
        ]);

        // LA LATITUD Y LA LONGITUD SE VA A ENVIAR DESDE LAS MARCAS
        // COORDENADAS DE PRUEBA PARA CHONGOYAPE
        // $latitude = "-6.655233565553968";
        // $longitude = "-79.4288214368059";
        // COORDENADAS DE PRUEBA PARA POMALCA
        // $latitude = "-6.770238151749822";
        // $longitude = "-79.78025980824428";
        // COORDENADAS DE PRUEBA PARA TUMAN
        // $latitude = "-6.740373513187599";
        // $longitude = "-79.70908382882749";
        $dbdata = DB::select("SELECT * from DataGreenMovil..ServiciosTransporteDetalleTest WHERE IdServicioTransporte = '03700000006R' and Item = '4'");

        foreach ($dbdata as $key => $value) {
            $coordinatesArray = explode(',', $dbdata[$key]->coordenadas_marca);
            // echo $key.', ';
            // Convertir la cadena en un array usando explode
            $latitude = $coordinatesArray[0];
            $longitude = $coordinatesArray[1];
            // echo $coordinatesArray[0].','.$coordinatesArray[1].'<br>';
            $response = $client->get('https://nominatim.openstreetmap.org/reverse', [
                'query' => [
                    'format' => 'json',
                    'lat' => $latitude,
                    'lon' => $longitude,
                ]
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            $localidad_marca = '';

            $address = $data['address'];
            return $address;
            // if(isset($address['village'])){
            //     echo $address['village']. ' - '.$address['town'];
            //     $localidad_marca = $address['village']. ' - '.$address['town'];
            // }else if(isset($address['hamlet'])){
            //     $localidad_marca = $address['hamlet']. ' - '.$address['town'];
            // }else if(isset($address['town'])){
            //     $localidad_marca = $address['town'];
            // }
            // $queryCoord = "UPDATE DataGreenMovil..ServiciosTransporteDetalleTest SET localidad_marca = '".$localidad_marca."' WHERE IdServicioTransporte = '03700000006R' AND Item = '".$dbdata[$key]->Item."'";
            // // echo $queryCoord;
            // DB::unprepared("UPDATE DataGreenMovil..ServiciosTransporteDetalleTest SET localidad_marca = '".$localidad_marca."' WHERE IdServicioTransporte = '03700000006R' AND Item = '".$dbdata[$key]->Item."'");
        }
        return '';


        // $response = $client->get('https://nominatim.openstreetmap.org/reverse', [
        //     'query' => [
        //         'format' => 'json',
        //         'lat' => $latitude,
        //         'lon' => $longitude,
        //     ]
        // ]);
        // $data = json_decode($response->getBody()->getContents(), true);

        // $address = $data['address'];
        // $town = $address['town'];
        // return $town;
    }

    public function obtenerCoordenadasRedonda(Request $request)
    {

        // PRIMERAS COORDENADAS: OBTENIDAS DE ANDROID
        $latitud_central = -6.656976666666666;
        $longitud_central = -79.42912666666666;
        $radio_metros = 38;

        // NÚMERO DE PUNTOS A GENERAR EN MAPS
        $numero_puntos = 10;

        // CREAMOS UN ARRAY PARA PODER ALMACENAR LAS COORDENADAS A LA REDONDE DE ACUERDO A LOS METROS INSERTADOS EN $radio_metros
        $coordenadas = [];

        // RADIO DE LA TIERRA EN METROS APROXIMADAMENTE (6.371.000)
        $radio_tierra = 6371000;

        // CONVERTIR LA DISTANCIA DEL RADIO DE METROS A RADIANES PARA OPERAR SOBRE LA MISMA UNIDAD DE MEDIDA
        $distancia_radianes = $radio_metros / $radio_tierra;

        // CONVERTIR LA LATITUD Y LONGITUD DE GRADOS A RADIANES
        $latitud_rad = deg2rad($latitud_central);
        $longitud_rad = deg2rad($longitud_central);

        // CALCULAR ÁNGULO ENTRE CADA PUNTO (360 GRADOS DIVIDIDO POR EL NÚMERO DE PUNTOS)
        $angulo_entre_puntos = 2 * M_PI / $numero_puntos;

        // Generar puntos equidistantes alrededor del círculo
        for ($i = 0; $i < $numero_puntos; $i++) {
            // Calcular el ángulo para este punto
            $angulo = $angulo_entre_puntos * $i;

            // Calcular las nuevas coordenadas
            $nueva_latitud = asin(sin($latitud_rad) * cos($distancia_radianes) + cos($latitud_rad) * sin($distancia_radianes) * cos($angulo));
            $nueva_longitud = $longitud_rad + atan2(sin($angulo) * sin($distancia_radianes) * cos($latitud_rad), cos($distancia_radianes) - sin($latitud_rad) * sin($nueva_latitud));

            // Convertir de radianes a grados
            $nueva_latitud = rad2deg($nueva_latitud);
            $nueva_longitud = rad2deg($nueva_longitud);

            // Almacenar las coordenadas en el formato requerido para la URL
            $coordenadas[] = $nueva_latitud . ',' . $nueva_longitud;
        }

        // Crear la cadena de la URL de Google Maps
        $coordenadas_str = implode('/', $coordenadas);
        $url_google_maps = 'https://www.google.com/maps/dir/' . $latitud_central . ',' . $longitud_central . '/' . $coordenadas_str;

        // Coordenadas proporcionadas para evaluar

        $latitud = -6.656694723415216;
        $longitud = -79.42893946616825;

        // $latitud = -6.656010042304066;
        // $longitud = -79.42985946386035;

        // -6.656010042304066, -79.42985946386035

        // Calcular la distancia entre las coordenadas proporcionadas y el punto central
        $dLat = deg2rad($latitud - $latitud_central);
        $dLon = deg2rad($longitud - $longitud_central);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($latitud_central)) * cos(deg2rad($latitud)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distancia = $radio_tierra * $c;

        $dentro_del_rango = $distancia <= $radio_metros;

        // Devolver la URL de google maps y si la coordenada está dentro del rango
        return response()->json([
            'url' => $url_google_maps,
            'dentro_del_rango' => $dentro_del_rango,
            'distancia' => $distancia
        ]);
    }
}

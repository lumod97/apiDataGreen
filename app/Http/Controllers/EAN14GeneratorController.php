<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EAN14GeneratorController extends Controller
{
    public function generarCodigoEAN14ConVerificadorFijo($numero_fijo, $cantidad) {
        $codigosGenerados = []; // Arreglo para almacenar los códigos generados
    
        while (count($codigosGenerados) < $cantidad) {
            // Generar un número base de 13 dígitos aleatorios
            $numeroBase = str_pad(rand(0, 9999999999999), 13, '0', STR_PAD_LEFT);
    
            // Calcular el dígito de control
            $sum = 0;
            $multiplicador = 3;
    
            // Recorrer el número de derecha a izquierda
            for ($i = strlen($numeroBase) - 1; $i >= 0; $i--) {
                $digito = (int)$numeroBase[$i];
                $sum += $digito * $multiplicador;
                $multiplicador = ($multiplicador == 3) ? 1 : 3; // Alternar entre 1 y 3
            }
    
            // Calcular el dígito de control
            $digitoControl = (10 - ($sum % 10)) % 10;
    
            // Si el dígito de control es 1, agregar el código al arreglo
            if ($digitoControl == $numero_fijo) {
                // $codigosGenerados[] = $numeroBase . $numero_fijo; // Agregar el número base con el dígito fijo
                $codigosGenerados[] = $numeroBase; // Agregar el número base con el dígito fijo
            }
        }
    
        // Devolver el arreglo con todos los códigos generados
        return response()->json(['codigos_ean14' => $codigosGenerados]);
    }
    
}

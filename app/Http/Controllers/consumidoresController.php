<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class consumidoresController extends Controller
{
    public function insertConsumidor(Request $request)
    {
        $params = [
            $request['id_consumidor'],
            $request['cp4']
        ];

        $result = DB::statement("INSERT INTO DataGreen..Dg_JerarquiaConsumidores (IdConsumidor, CP4) VALUES (?, ?)", $params);
        return $result;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SensorDataController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables(Auth::user()->sensorReadings())->toJson();
        }

        return view('resource.index', [
            'name'=> 'data',
            'columns' => [
                'id',
                'distance_cm',
                'water_percent',
                'water_temp',
                'tds',
                'air_temp',
                'air_humidity',
                'timestamp',
                'created_at',
            ],
            'action' => false,
            'order' => [[0, 'desc']],
        ]);
    }
}



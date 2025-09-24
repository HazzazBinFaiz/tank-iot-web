<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorDataController extends Controller
{
    public function __invoke(Request $request)
    {
        // Find the user based on the 'X-API-Key' header
        $id = \App\Lib\Hexer::decode($request->get('api_key', $request->header('X-API-Key')));
        $user = User::findOrFail($id);

        // Decode the JSON payload from the request body
        $records = $request->json()->all();

        $records = $records['records'] ?? $records;

        // Check if the payload is a valid array and contains data
        if (!is_array($records) || empty($records)) {
            Log::error('Invalid or empty sensor data payload.', ['user_id' => $user->id]);
            return response()->json(['message' => 'Invalid or empty data payload.'], 400);
        }

        $savedCount = 0;
        foreach ($records as $record) {
            // Attempt to create and save a new SensorReading record for each item in the batch
            try {
                $user->sensorReadings()->create([
                    'timestamp' => $record['timestamp'] ?? now()->timestamp,
                    'distance_cm' => $record['distance_cm'],
                    'water_percent' => $record['percent'], // Note: Arduino sends 'percent', model expects 'water_percent'
                    'air_temp' => $record['airTemp'],
                    'air_humidity' => $record['airHum'],
                    'water_temp' => $record['waterTemp'],
                    'tds' => $record['tds'],
                    'pump_on' => $record['pumpOn'],
                ]);
                $savedCount++;
            } catch (\Exception $e) {
                // Log any errors that occur during the saving process
                Log::error('Failed to save a sensor record.', [
                    'user_id' => $user->id,
                    'record' => $record,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info('Successfully saved sensor data batch.', ['user_id' => $user->id, 'records_saved' => $savedCount]);

        return response()->json([
            'message' => "Successfully received and stored $savedCount records.",
            'records_saved' => $savedCount
        ], 201);
    }

    public function get(Request $request)
    {
        // Find the user based on the 'X-API-Key' header
        $id = \App\Lib\Hexer::decode($request->get('api_key', $request->header('X-API-Key')));
        $user = User::findOrFail($id);

        $record  = $request->validate([
            'timestamp' => 'required',
            'distance_cm' => 'required',
            'percent' => 'required',
            'airTemp' => 'required',
            'airHum' => 'required',
            'waterTemp' => 'required',
            'tds' => 'required',
            'pumpOn' => 'required',
        ]);


        try {
            $user->sensorReadings()->create([
                'timestamp' => $record['timestamp'] ?? now()->timestamp,
                'distance_cm' => $record['distance_cm'],
                'water_percent' => $record['percent'], // Note: Arduino sends 'percent', model expects 'water_percent'
                'air_temp' => $record['airTemp'],
                'air_humidity' => $record['airHum'],
                'water_temp' => $record['waterTemp'],
                'tds' => $record['tds'],
                'pump_on' => $record['pumpOn'],
            ]);
        } catch (\Exception $e) {
            // Log any errors that occur during the saving process
            Log::error('Failed to save a sensor record.', [
                'user_id' => $user->id,
                'record' => $record,
                'error' => $e->getMessage()
            ]);
        }
        Log::info('Successfully saved sensor data batch.', ['user_id' => $user->id]);

        return response()->json([
            'message' => "Successfully received and stored record.",
        ], 201);
    }
}

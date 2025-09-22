<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorDataController extends Controller
{

    public function getSensorData(Request $request)
    {
        $id = \App\Lib\Hexer::decode($request->get('api_key', $request->header('X-API-Key')));
        $user = User::findOrFail($id);

        // 2. Determine the time range from the request
        $range = $request->query('range', '15m');
        $currentTime = now();

        switch ($range) {
            case '15m':
                $startTime = $currentTime->copy()->subMinutes(15);
                break;
            case '30m':
                $startTime = $currentTime->copy()->subMinutes(30);
                break;
            case '1h':
                $startTime = $currentTime->copy()->subHour();
                break;
            case '6h':
                $startTime = $currentTime->copy()->subHours(6);
                break;
            case '1d':
                $startTime = $currentTime->copy()->subDay();
                break;
            case '2d':
                $startTime = $currentTime->copy()->subDays(2);
                break;
            case '1w':
                $startTime = $currentTime->copy()->subWeek();
                break;
            case '1m':
                $startTime = $currentTime->copy()->subMonth();
                break;
            default:
                $startTime = $currentTime->copy()->subMinutes(15);
                break;
        }

        // 3. Fetch data from the database within the specified time range
        // For a real application, you would use Eloquent or the Query Builder.
        // This is a placeholder for demonstration.
        $records = SensorReading::where('timestamp', '>=', $startTime->timestamp)
            ->orderBy('timestamp', 'asc')
            ->get();

        // 4. Return the data as a JSON response
        return response()->json(['records' => $records]);
    }

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
}

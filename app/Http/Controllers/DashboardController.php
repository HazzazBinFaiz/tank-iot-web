<?php

namespace App\Http\Controllers;

use App\Lib\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $range = $this->findRange($request);

        if ($request->ajax()) {
            return Auth::user()->sensorReadings()
                ->whereBetween('created_at', $range)
                ->orderBy('id')
                ->when($request->has('after_id'), static function ($query) use ($request) {
                    return $query->where('id', '>', $request->after_id);
                })
                ->get()
                ->toJson();
        }

        return view('sensorData.index');
    }

    private function findRange(Request $request)
    {
        // Check for custom start_date and end_date first
        if ($request->has(['start_date', 'end_date'])) {
            try {
                $start = \Carbon\Carbon::parse($request->input('start_date'));
                $end = \Carbon\Carbon::parse($request->input('end_date'));

                // Prevent invalid range: if end < start, fallback to last 15 min
                if ($end->lessThan($start)) {
                    return [now()->subMinutes(15), now()];
                }

                return [$start, $end];
            } catch (\Exception $e) {
                // fallback in case of parse error
                return [now()->subMinutes(15), now()];
            }
        }

        // Otherwise, handle predefined ranges
        if ($request->has('range')) {
            $range = $request->input('range');

            if ($range === '15m') {
                return [now()->subMinutes(15), now()];
            } elseif ($range === '30m') {
                return [now()->subMinutes(30), now()];
            } elseif ($range === '1h') {
                return [now()->subHour(), now()];
            } elseif ($range === '2h') {
                return [now()->subHours(2), now()];
            } elseif ($range === '1d') {
                return [now()->subDay(), now()];
            } elseif ($range === '1w') {
                return [now()->subWeek(), now()];
            } elseif ($range === '1m') {
                return [now()->subMonth(), now()];
            } else {
                return [now()->subMinutes(15), now()];
            }
        }

        // Default to last 15 min
        return [now()->subMinutes(15), now()];
    }
}

<?php

namespace App\Http\Controllers;

use App\Contracts\VisitTrackingServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VisitController extends Controller
{
    public function __construct(
        private VisitTrackingServiceInterface $trackingService
    ) {
    }

    public function track(Request $request)
    {
        try {
            Log::info('1. Request received', $request->all());

            $validated = $request->validate([
                'device' => 'nullable|string',
                'browser' => 'nullable|string',
                'page_url' => 'nullable|string',
            ]);

            Log::info('2. Validation passed', ['validated' => $validated]);

            $ip = $request->ip();
            Log::info('3. IP: ' . $ip);

            $result = $this->trackingService->trackVisit($validated, $ip);

            Log::info('4. Result: ', $result);

            return response()->json($result, 201);
        } catch (\Exception $e) {
            Log::error('ERROR: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
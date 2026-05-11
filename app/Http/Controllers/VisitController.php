<?php

namespace App\Http\Controllers;

use App\Contracts\VisitTrackingServiceInterface;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function __construct(
        private VisitTrackingServiceInterface $trackingService
    ) {}

    public function track(Request $request)
    {
        try {
            Log::info('Visit track request received', $request->all());

            $validated = $request->validate([
                'device' => 'nullable|string',
                'browser' => 'nullable|string',
                'page_url' => 'nullable|string',
            ]);

            $ip = $request->ip();

            $result = $this->trackingService->trackVisit($validated, $ip);

            return response()->json($result, 201);
        } catch (\Exception $e) {
            Log::error('Track error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }

    }
}

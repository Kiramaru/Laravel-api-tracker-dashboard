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

            Log::info('VisitController: request received', $request->all());
            $validated = $request->validate([ //Если поле заполнено, то оно должно быть строкой. Если поле не заполнено, то оно может быть null.

                'ip' => 'nullable|string',
                'city' => 'nullable|string',
                'device' => 'nullable|string',
                'browser' => 'nullable|string',
                'page_url' => 'nullable|string',

            ]);



            //Получение ip
            $ip = $request->ip();

            Log::info('VisitController: validated', ['ip' => $ip, 'validated' => $validated]);

            // Передаём всё в сервис
            $result = $this->trackingService->trackVisit($validated, $ip);
            Log::info('VisitController: result', $result);

            return response()->json($result, 201);
        } catch (\Exception $e) {
            Log::error('Visit tracking error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }


    }
}

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
        $validated = $request->validate([ //Если поле заполнено, то оно должно быть строкой. Если поле не заполнено, то оно может быть null.

            'ip' => 'nullable|string',
            'city' => 'nullable|string',
            'device' => 'nullable|string',
            'browser' => 'nullable|string',
            'page_url' => 'nullable|string',

        ]);

        //Получение ip
        $ip = $request->ip();

        // Передаём всё в сервис
        $result = $this->trackingService->trackVisit($validated, $ip);

        return response()->json($result, 201);
    }
}

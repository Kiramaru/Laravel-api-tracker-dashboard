<?php

namespace App\Http\Controllers;

use App\Contracts\VisitRepositoryInterface;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function __construct(
        private VisitRepositoryInterface $visitRepository
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

        $visit = $this->visitRepository->create($validated);//Создание объекта Visit с данными из запроса

        return response()->json(['success' => true, 'message' => 'Visit tracked'], 201);//Ответ в формате JSON с сообщением об успешном отслеживании визита и статусом
    }
}

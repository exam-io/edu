<?php

namespace Modules\Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Exam\Application\Contracts\ExamFacadeServiceInterface;

class ExamController extends Controller
{
    public function overview(ExamFacadeServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $service->overview(),
        ]);
    }
}

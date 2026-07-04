<?php

use Illuminate\Support\Facades\Route;
use Modules\Assessment\Http\Controllers\AssessmentController;
use Modules\Assessment\Http\Controllers\AssessmentAttemptController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('assessments', [AssessmentController::class, 'index'])->middleware('identity.permission:assessment.view');
    Route::post('assessments', [AssessmentController::class, 'store'])->middleware('identity.permission:assessment.create');
    Route::get('assessments/{id}', [AssessmentController::class, 'show'])->middleware('identity.permission:assessment.view');
    Route::put('assessments/{id}', [AssessmentController::class, 'update'])->middleware('identity.permission:assessment.update');
    Route::delete('assessments/{id}', [AssessmentController::class, 'destroy'])->middleware('identity.permission:assessment.delete');
    Route::post('assessments/{id}/publish', [AssessmentController::class, 'publish'])->middleware('identity.permission:assessment.publish');

    Route::post('assessments/{id}/start', [AssessmentAttemptController::class, 'start'])->middleware('identity.permission:assessment_attempt.create');
    Route::post('assessments/{id}/save-answer', [AssessmentAttemptController::class, 'saveAnswer'])->middleware(['identity.permission:assessment_attempt.update', 'throttle:120,1']);
    Route::post('assessments/{id}/submit', [AssessmentAttemptController::class, 'submit'])->middleware('identity.permission:assessment_attempt.submit');
    Route::post('assessments/{id}/attempts/{attemptId}/evaluate', [AssessmentAttemptController::class, 'evaluate'])->middleware('identity.permission:submission.evaluate');
    Route::get('assessments/{id}/result', [AssessmentAttemptController::class, 'result'])->middleware('identity.permission:assessment_result.view');
});

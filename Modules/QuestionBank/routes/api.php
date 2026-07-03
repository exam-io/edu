<?php

use Illuminate\Support\Facades\Route;
use Modules\QuestionBank\Http\Controllers\QuestionSetController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('question-sets', [QuestionSetController::class, 'index'])->middleware('identity.permission:question.bank.view');
    Route::post('question-sets', [QuestionSetController::class, 'store'])->middleware('identity.permission:question.bank.create');
    Route::get('question-sets/{id}', [QuestionSetController::class, 'show'])->middleware('identity.permission:question.bank.view');
    Route::put('question-sets/{id}', [QuestionSetController::class, 'update'])->middleware('identity.permission:question.bank.update');
    Route::delete('question-sets/{id}', [QuestionSetController::class, 'destroy'])->middleware('identity.permission:question.bank.delete');
});

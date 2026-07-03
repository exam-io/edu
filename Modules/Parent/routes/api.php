<?php

use Illuminate\Support\Facades\Route;
use Modules\Parent\Http\Controllers\ParentController;

Route::middleware(['tenant', 'auth:sanctum'])->group(function (): void {
    Route::get('parents', [ParentController::class, 'index'])->middleware('identity.permission:parent.view');
    Route::post('parents', [ParentController::class, 'store'])->middleware('identity.permission:parent.create');
    Route::get('parents/{id}', [ParentController::class, 'show'])->middleware('identity.permission:parent.view');
    Route::put('parents/{id}', [ParentController::class, 'update'])->middleware('identity.permission:parent.update');
    Route::delete('parents/{id}', [ParentController::class, 'destroy'])->middleware('identity.permission:parent.delete');
});

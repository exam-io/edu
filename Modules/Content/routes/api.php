<?php

use Illuminate\Support\Facades\Route;
use Modules\Content\Http\Controllers\ContentItemController;
use Modules\Content\Http\Controllers\CourseSectionController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('course-sections', [CourseSectionController::class, 'index'])->middleware('identity.permission:content.section.view');
    Route::post('course-sections', [CourseSectionController::class, 'store'])->middleware('identity.permission:content.section.create');
    Route::get('course-sections/{id}', [CourseSectionController::class, 'show'])->middleware('identity.permission:content.section.view');
    Route::put('course-sections/{id}', [CourseSectionController::class, 'update'])->middleware('identity.permission:content.section.update');
    Route::delete('course-sections/{id}', [CourseSectionController::class, 'destroy'])->middleware('identity.permission:content.section.delete');

    Route::get('content-items', [ContentItemController::class, 'index'])->middleware('identity.permission:content.item.view');
    Route::post('content-items', [ContentItemController::class, 'store'])->middleware('identity.permission:content.item.create');
    Route::get('content-items/{id}', [ContentItemController::class, 'show'])->middleware('identity.permission:content.item.view');
    Route::put('content-items/{id}', [ContentItemController::class, 'update'])->middleware('identity.permission:content.item.update');
    Route::delete('content-items/{id}', [ContentItemController::class, 'destroy'])->middleware('identity.permission:content.item.delete');
});

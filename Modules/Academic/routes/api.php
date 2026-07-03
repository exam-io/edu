<?php

use Illuminate\Support\Facades\Route;
use Modules\Academic\Http\Controllers\BatchController;
use Modules\Academic\Http\Controllers\ClassController;
use Modules\Academic\Http\Controllers\DepartmentController;
use Modules\Academic\Http\Controllers\ProgramController;
use Modules\Academic\Http\Controllers\SectionController;
use Modules\Academic\Http\Controllers\SubjectController;

Route::middleware(['tenant', 'auth:sanctum'])->group(function (): void {
    Route::get('departments', [DepartmentController::class, 'index'])->middleware('identity.permission:department.view');
    Route::post('departments', [DepartmentController::class, 'store'])->middleware('identity.permission:department.create');
    Route::get('departments/{id}', [DepartmentController::class, 'show'])->middleware('identity.permission:department.view');
    Route::put('departments/{id}', [DepartmentController::class, 'update'])->middleware('identity.permission:department.update');
    Route::delete('departments/{id}', [DepartmentController::class, 'destroy'])->middleware('identity.permission:department.delete');

    Route::get('programs', [ProgramController::class, 'index'])->middleware('identity.permission:program.view');
    Route::post('programs', [ProgramController::class, 'store'])->middleware('identity.permission:program.create');
    Route::get('programs/{id}', [ProgramController::class, 'show'])->middleware('identity.permission:program.view');
    Route::put('programs/{id}', [ProgramController::class, 'update'])->middleware('identity.permission:program.update');
    Route::delete('programs/{id}', [ProgramController::class, 'destroy'])->middleware('identity.permission:program.delete');

    Route::get('classes', [ClassController::class, 'index'])->middleware('identity.permission:class.view');
    Route::post('classes', [ClassController::class, 'store'])->middleware('identity.permission:class.create');
    Route::get('classes/{id}', [ClassController::class, 'show'])->middleware('identity.permission:class.view');
    Route::put('classes/{id}', [ClassController::class, 'update'])->middleware('identity.permission:class.update');
    Route::delete('classes/{id}', [ClassController::class, 'destroy'])->middleware('identity.permission:class.delete');

    Route::get('sections', [SectionController::class, 'index'])->middleware('identity.permission:section.view');
    Route::post('sections', [SectionController::class, 'store'])->middleware('identity.permission:section.create');
    Route::get('sections/{id}', [SectionController::class, 'show'])->middleware('identity.permission:section.view');
    Route::put('sections/{id}', [SectionController::class, 'update'])->middleware('identity.permission:section.update');
    Route::delete('sections/{id}', [SectionController::class, 'destroy'])->middleware('identity.permission:section.delete');

    Route::get('batches', [BatchController::class, 'index'])->middleware('identity.permission:batch.view');
    Route::post('batches', [BatchController::class, 'store'])->middleware('identity.permission:batch.create');
    Route::get('batches/{id}', [BatchController::class, 'show'])->middleware('identity.permission:batch.view');
    Route::put('batches/{id}', [BatchController::class, 'update'])->middleware('identity.permission:batch.update');
    Route::delete('batches/{id}', [BatchController::class, 'destroy'])->middleware('identity.permission:batch.delete');

    Route::get('subjects', [SubjectController::class, 'index'])->middleware('identity.permission:subject.view');
    Route::post('subjects', [SubjectController::class, 'store'])->middleware('identity.permission:subject.create');
    Route::get('subjects/{id}', [SubjectController::class, 'show'])->middleware('identity.permission:subject.view');
    Route::put('subjects/{id}', [SubjectController::class, 'update'])->middleware('identity.permission:subject.update');
    Route::delete('subjects/{id}', [SubjectController::class, 'destroy'])->middleware('identity.permission:subject.delete');
});

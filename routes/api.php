<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\DepartmentClassController;
use App\Http\Controllers\DepartmentClassSubjectController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InstituteController;
use App\Http\Controllers\InstituteUpdateController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response(['Laravel' => app()->version()], 200);
}); 

Route::get('/app', AppController::class);

Route::put('/app/institute/name', [InstituteUpdateController::class, 'name']);

Route::post('/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [UserController::class, 'user']);
    Route::post('/logout', [UserController::class, 'logout']);

    Route::get('/institute', [InstituteController::class, 'index']);
    Route::get('/institute/{key}', [InstituteController::class, 'show']);
    Route::put('/institute/{key}', [InstituteController::class, 'update']);

    Route::apiResource('packages', PackageController::class);

    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('departments.classes', DepartmentClassController::class);
    Route::apiResource('departments.classes.subjects', DepartmentClassSubjectController::class);
});

Route::any('/{any}', function ($any) {
    return response("'{$any}' Not Found!", 404);
})->where('any', '.*');

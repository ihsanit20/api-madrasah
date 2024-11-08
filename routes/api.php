<?php

use App\Http\Controllers\AcademicSessionAcademicClassController;
use App\Http\Controllers\ADM\AdmissionFormController;
use App\Http\Controllers\AnnualFeeController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommonDataController;
use App\Http\Controllers\DepartmentAcademicSessionAcademicClassAcademicSubjectController;
use App\Http\Controllers\DepartmentAcademicSessionAcademicClassController;
use App\Http\Controllers\DepartmentAcademicSessionAcademicClassPackageFeeController;
use App\Http\Controllers\DepartmentAcademicSessionController;
use App\Http\Controllers\DepartmentClassController;
use App\Http\Controllers\DepartmentClassSubjectController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\FeeNameController;
use App\Http\Controllers\InstituteController;
use App\Http\Controllers\InstituteUpdateController;
use App\Http\Controllers\LocationBDController;
use App\Http\Controllers\MonthlyFeeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZamatController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
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

Route::post('/admin-register', [AuthController::class, 'adminRegister']);
Route::post('/admin-login', [AuthController::class, 'adminLogin']);
Route::post('/student-login', [AuthController::class, 'studentLogin']);


Route::get('/app', AppController::class);

Route::put('/app/institute/name', [InstituteUpdateController::class, 'name']);

Route::post('/login', [UserController::class, 'login']);

Route::get('/institute', [InstituteController::class, 'index']);


Route::prefix('common-data')->group(function () {
    Route::get('/blood-groups', [CommonDataController::class, 'getBloodGroup']);
    Route::get('/genders', [CommonDataController::class, 'getGenders']);
});

Route::prefix('location-bd')->group(function () {
    Route::get('/divisions', [LocationBDController::class, 'divisions']);
    Route::get('/divisions/{division}/districts', [LocationBDController::class, 'divisionDistricts']);
    
    Route::get('/districts', [LocationBDController::class, 'districts']);
    Route::get('/districts/{district}/areas', [LocationBDController::class, 'districtAreas']);

    Route::get('/areas', [LocationBDController::class, 'areas']);
});

Route::get('departments', [DepartmentController::class, 'index']);

Route::get('sections', [SectionController::class, 'index']); 
Route::get('sections/{section}', [SectionController::class, 'show']); 

Route::get('zamats', [ZamatController::class, 'index']); 
Route::get('zamats/{zamat}', [ZamatController::class, 'show']); 

Route::get('/subjects', [SubjectController::class, 'index']);
Route::get('/subjects/{subject}', [SubjectController::class, 'show']);

Route::get('/fee-names', [FeeNameController::class, 'index']);
Route::get('/fee-names/{id}', [FeeNameController::class, 'show']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', [UserController::class, 'getUser']);
    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::post('/user', [UserController::class, 'addUser']);
    Route::put('/user/{id}', [UserController::class, 'updateUser']);
    Route::delete('/user/{id}', [UserController::class, 'deleteUser']);

    Route::post('/logout', [UserController::class, 'logout']);

    Route::get('/institute/{key}', [InstituteController::class, 'show']);
    Route::put('/institute/{key}', [InstituteController::class, 'update']);

    Route::post('sections', [SectionController::class, 'store']); 
    Route::put('sections/{section}', [SectionController::class, 'update']); 
    Route::delete('sections/{section}', [SectionController::class, 'destroy']); 

    Route::post('zamats', [ZamatController::class, 'store']); 
    Route::put('zamats/{zamat}', [ZamatController::class, 'update']); 
    Route::delete('zamats/{zamat}', [ZamatController::class, 'destroy']); 

    Route::post('/subjects', [SubjectController::class, 'store']);
    Route::put('/subjects/{subject}', [SubjectController::class, 'update']);
    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy']);

    Route::post('/fee-names', [FeeNameController::class, 'store']);
    Route::put('/fee-names/{id}', [FeeNameController::class, 'update']);
    Route::patch('/fee-names/{id}', [FeeNameController::class, 'update']);
    Route::delete('/fee-names/{id}', [FeeNameController::class, 'destroy']);

    Route::apiResource('packages', PackageController::class);
    Route::apiResource('fees', FeeController::class);

    Route::apiResource('monthly-fees', MonthlyFeeController::class);
    Route::apiResource('annual-fees', AnnualFeeController::class);

    Route::apiResource('departments', DepartmentController::class)->except('index');
    Route::apiResource('departments.classes', DepartmentClassController::class);
    Route::apiResource('departments.classes.subjects', DepartmentClassSubjectController::class);
    
    Route::apiResource('departments.academic-sessions', DepartmentAcademicSessionController::class);

    Route::apiResource('academic-sessions.academic-classes', AcademicSessionAcademicClassController::class);

    Route::apiResource('departments.academic-sessions.academic-classes', DepartmentAcademicSessionAcademicClassController::class);

    Route::apiResource('departments.academic-sessions.academic-classes.academic-subjects', DepartmentAcademicSessionAcademicClassAcademicSubjectController::class);
    
    Route::apiResource('departments.academic-sessions.academic-classes.package-fees', DepartmentAcademicSessionAcademicClassPackageFeeController::class);

    // csm : class and student management
    Route::prefix('csm')->group(base_path('routes/api/csm.php'));

    // adm : admission management
    Route::prefix('adm')->group(base_path('routes/api/adm.php'));
});

Route::get('/php-artisan/{command?}', function ($command = 'list') {

    // dd(DB::connection('dynamic'));

    $allowCommands = [
        "migrate:install",
        "migrate:status",
        "migrate",
        // "migrate:rollback",
    ];

    if($command == 'list') {
        return $allowCommands;
    }

    if(!in_array($command, $allowCommands)) {
        return "Not Allow";
    }

    $parameters = [];

    if(Config::get("database.default") == "dynamic") {

        if(in_array($command, ["migrate", "migrate:status", "migrate:rollback"])) {
            $parameters["--path"] = "/database/migrations/clients";
        }
    }

    Artisan::call($command, $parameters);

    dd(Artisan::output());
});

Route::any('/{any}', function ($any) {
    return response("'{$any}' Not Found!", 404);
})->where('any', '.*');


// for client DB migration command 'php artisan migrate:clients'
// for client DB migration status command 'php artisan migrate:clients --status'
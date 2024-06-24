<?php

use App\Http\Controllers\ADM\AdmissionFormController;

use Illuminate\Support\Facades\Route;

Route::prefix('admission-forms')->group(function () {
    Route::get('/{admission_form}/admission-test', [AdmissionFormController::class, 'admissionTestShow']);
    Route::put('/{admission_form}/admission-test', [AdmissionFormController::class, 'admissionTestUpdate']);
    
    Route::get('/{admission_form}/admission-fee', [AdmissionFormController::class, 'admissionFeeShow']);
    Route::put('/{admission_form}/admission-fee', [AdmissionFormController::class, 'admissionFeeUpdate']);
    
    Route::get('/{admission_form}/admission-completion', [AdmissionFormController::class, 'admissionCompletionShow']);
    Route::put('/{admission_form}/admission-completion', [AdmissionFormController::class, 'admissionCompletionUpdate']);
});

Route::apiResource('/admission-forms', AdmissionFormController::class);
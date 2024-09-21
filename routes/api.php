<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\api\auth\ForgotPasswordController;
use App\Http\Controllers\api\auth\ResetPasswordController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\EmploymentDetailsController;
use App\Http\Controllers\EventImageController;
use App\Http\Controllers\InformationPostController;
use App\Http\Controllers\JobApplicantController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\PersonController;

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/password/forgot', [ForgotPasswordController::class, 'forgotPassword']);
    Route::post('/password/reset', [ResetPasswordController::class, 'resetPassword']);
    Route::get('/information/pub', [EventImageController::class, 'index']);

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
        Route::post('/me', [AuthController::class, 'me'])->name('me');

        // CRUD routes for Person resource
        Route::apiResource('persons', PersonController::class);
        Route::apiResource('employers', EmployerController::class);
        Route::apiResource('information', InformationPostController::class);
        Route::get('information/user/{userId}', [InformationPostController::class, 'getPostsByUser']);
        Route::apiResource('jobs', JobPostController::class);
        Route::post('jobs/all', [JobPostController::class, 'getAllJobs']);
        Route::post('jobs/search', [JobPostController::class, 'fetchJobBySearchKey']);
        Route::get('jobs/user/{userId}', [JobPostController::class, 'getJobPostsByUser']);
        Route::apiResource('addresses', AddressController::class);
        Route::apiResource('employment', EmploymentDetailsController::class);
        Route::apiResource('eventImages', EventImageController::class);
        Route::get('eventImages/information/{postid}', [EventImageController::class, 'getImageByPostId']);
        Route::apiResource('jobApplicant', JobApplicantController::class);
    });
});

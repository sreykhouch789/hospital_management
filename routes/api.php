<?php

use App\Http\Controllers\Api\AppointmentApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BillApiController;
use App\Http\Controllers\Api\DoctorApiController;
use App\Http\Controllers\Api\PatientApiController;
use App\Http\Controllers\Api\PrescriptionApiController;
use App\Http\Controllers\Api\RoomApiController;
use App\Http\Controllers\Api\StatsApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Hospital Management REST API Routes (v1)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // Public Authentication Routes
    Route::post('/auth/register', [AuthApiController::class, 'register']);
    Route::post('/auth/login', [AuthApiController::class, 'login']);

    // Hospital Stats / KPI
    Route::get('/stats', [StatsApiController::class, 'index']);

    // Doctors Endpoints
    Route::get('/doctors', [DoctorApiController::class, 'index']);
    Route::post('/doctors', [DoctorApiController::class, 'store']);
    Route::get('/doctors/{id}', [DoctorApiController::class, 'show']);
    Route::put('/doctors/{id}', [DoctorApiController::class, 'update']);
    Route::delete('/doctors/{id}', [DoctorApiController::class, 'destroy']);

    // Patients Endpoints
    Route::get('/patients', [PatientApiController::class, 'index']);
    Route::post('/patients', [PatientApiController::class, 'store']);
    Route::get('/patients/{id}', [PatientApiController::class, 'show']);
    Route::put('/patients/{id}', [PatientApiController::class, 'update']);
    Route::delete('/patients/{id}', [PatientApiController::class, 'destroy']);

    // Appointments Endpoints
    Route::get('/appointments', [AppointmentApiController::class, 'index']);
    Route::post('/appointments', [AppointmentApiController::class, 'store']);
    Route::get('/appointments/{id}', [AppointmentApiController::class, 'show']);
    Route::patch('/appointments/{id}/status', [AppointmentApiController::class, 'updateStatus']);
    Route::delete('/appointments/{id}', [AppointmentApiController::class, 'destroy']);

    // Prescriptions Endpoints
    Route::get('/prescriptions', [PrescriptionApiController::class, 'index']);
    Route::post('/prescriptions', [PrescriptionApiController::class, 'store']);
    Route::get('/prescriptions/{id}', [PrescriptionApiController::class, 'show']);
    Route::delete('/prescriptions/{id}', [PrescriptionApiController::class, 'destroy']);

    // Rooms & Beds Allocation Endpoints
    Route::get('/rooms', [RoomApiController::class, 'index']);
    Route::post('/rooms', [RoomApiController::class, 'store']);
    Route::patch('/rooms/{id}/assign', [RoomApiController::class, 'assignPatient']);
    Route::patch('/rooms/{id}/discharge', [RoomApiController::class, 'dischargePatient']);

    // Billing & Invoices Endpoints
    Route::get('/bills', [BillApiController::class, 'index']);
    Route::post('/bills', [BillApiController::class, 'store']);
    Route::get('/bills/{id}', [BillApiController::class, 'show']);
    Route::patch('/bills/{id}/status', [BillApiController::class, 'updateStatus']);

    // Authenticated User Routes (Sanctum protected)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthApiController::class, 'me']);
        Route::post('/auth/logout', [AuthApiController::class, 'logout']);
    });
});

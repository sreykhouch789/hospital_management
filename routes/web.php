<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Doctors Management
Route::resource('doctors', DoctorController::class)->only(['index', 'store', 'destroy']);

// Patients Registry
Route::resource('patients', PatientController::class)->only(['index', 'store', 'destroy']);

// Appointments Scheduling
Route::resource('appointments', AppointmentController::class)->only(['index', 'store', 'destroy']);
Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');

// Medical Prescriptions
Route::resource('prescriptions', PrescriptionController::class)->only(['index', 'store', 'destroy']);

// Rooms & Beds
Route::resource('rooms', RoomController::class)->only(['index', 'store']);
Route::patch('/rooms/{room}/assign', [RoomController::class, 'assignPatient'])->name('rooms.assign');
Route::patch('/rooms/{room}/discharge', [RoomController::class, 'dischargePatient'])->name('rooms.discharge');

// Billing & Invoices
Route::resource('bills', BillController::class)->only(['index', 'store']);
Route::patch('/bills/{bill}/status', [BillController::class, 'updateStatus'])->name('bills.updateStatus');

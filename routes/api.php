<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MedicineController;
use App\Http\Controllers\Api\PharmacyController;
use App\Http\Controllers\Api\AdminController;

// Auth
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
});

// للمرضى - بدون تسجيل دخول
Route::get('/medicines', [MedicineController::class, 'index']);
Route::get('/pharmacies', [PharmacyController::class, 'index']);
Route::get('/pharmacies/{id}', [PharmacyController::class, 'show']);

// للصيدلاني - محمي
Route::middleware(['auth:sanctum', 'role:pharmacist'])->group(function () {
    Route::post('/medicines', [MedicineController::class, 'store']);
    Route::put('/medicines/{id}', [MedicineController::class, 'update']);
    Route::delete('/medicines/{id}', [MedicineController::class, 'destroy']);
    Route::patch('/medicines/{id}/availability', [MedicineController::class, 'updateAvailability']);
    Route::put('/pharmacy/profile', [PharmacyController::class, 'update']);
});

// للأدمن - محمي
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/stats', [AdminController::class, 'stats']);
    Route::get('/admin/reports', [AdminController::class, 'reports']);
    Route::get('/admin/activity-log', [AdminController::class, 'activityLog']);
    Route::post('/admin/pharmacists', [AdminController::class, 'addPharmacist']);
});
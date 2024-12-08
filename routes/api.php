<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Design;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubscriptionController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/process', [DesignController::class, 'create'])->name('designs.create');
Route::get('/demo/designs/{category}/{limit}', [DesignController::class, 'getDemoDesigns'])->name('designs.getDemo');
Route::get('/designs/list', [DesignController::class, 'getList'])->name('designs.getList');
Route::post('/designs/{id}/update-order', [DesignController::class, 'updateOrder']);
Route::get('/regions/search', [RegionController::class, 'searchRegions']);
Route::get('/regions', [RegionController::class, 'getAllRegions']);
Route::post('/register-legal-entity', [SupplierController::class, 'registerCompany']);
Route::get('/check-company/{inn}', [SupplierController::class, 'checkCompany']);
Route::post('register-individual', [RegisterController::class, 'create']);
Route::post('register-legal-entity', [RegisterController::class, 'registerLegalEntity']);
Route::post('email/verify/{id}', [VerificationController::class, 'verifyEmail'])
    ->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'sendVerificationEmail'])
    ->name('verification.resend');
    Route::get('email/verify/{id}', [VerificationController::class, 'verifyEmail'])
    ->name('verification.verify');
    Route::get('/projects/{projectId}/available-executors', [SupplierController::class, 'getAvailableExecutors']);
    Route::post('/projects/{projectId}/contact-executor', [MessageController::class, 'contactExecutor']);
Route::post('/foundation/select-settings', [ProjectController::class, 'selectFoundationSettings']);
Route::post('/foundation/generate-order', [ProjectController::class, 'generateFoundationOrder']);

// Subscriptions
Route::apiResource('subscriptions', SubscriptionController::class);
Route::post('subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel']);
Route::post('subscriptions/{subscription}/pause', [SubscriptionController::class, 'pause']);
Route::post('subscriptions/{subscription}/resume', [SubscriptionController::class, 'resume']);
Route::post('subscriptions/{subscription}/renew', [SubscriptionController::class, 'renew']);
Route::get('subscriptions/active-for-entity', [SubscriptionController::class, 'getActiveForEntity']);
Route::post('subscriptions/create-for-entity', [SubscriptionController::class, 'createForEntity']);

// Tinkoff
Route::post('tinkoff/init', [OrderController::class, 'initOrder']);
Route::post('tinkoff/callback', [OrderController::class, 'tinkoffPayCallback']);

// Auth
Route::post('register-supplier', [RegisterController::class, 'createSupplier'])->name('register-supplier');


// Messages
Route::get('/users/{id}', function ($id) {
    $user = \App\Models\User::find($id);
    if ($user) {
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->supplier ? $user->supplier->company_name : $user->name
            ]
        ]);
    }
    return response()->json(['success' => false]);
});
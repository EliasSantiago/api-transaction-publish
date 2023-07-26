<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use OpenApi\Annotations as OA;

use App\Http\Controllers\Api\Auth\{
    AuthController
};

use App\Http\Controllers\Api\{
    HealthCheckController,
    WalletController,
    TransactionController
};

Route::get('/health-check', [HealthCheckController::class, 'healthCheck']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);

    Route::post('/wallet', [WalletController::class, 'store']);
    Route::get('/wallet/{wallet_id}', [WalletController::class, 'show']);

    Route::post('/transaction', [TransactionController::class, 'store'])->middleware('checkTransactionPayer');
});
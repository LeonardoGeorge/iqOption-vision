<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\SignalController;

// Dashboard Principal
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Rotas de Assets
Route::prefix('assets')->group(function () {
    Route::get('/', [AssetController::class, 'index'])->name('assets.index');
    Route::post('/sync', [AssetController::class, 'syncAssets'])->name('assets.sync');
    Route::post('/{id}/toggle', [AssetController::class, 'toggleActive'])->name('assets.toggle');
});

// Rotas de Sinais
Route::prefix('signals')->group(function () {
    Route::get('/', [SignalController::class, 'index'])->name('signals.index');
    Route::get('/{id}', [SignalController::class, 'show'])->name('signals.show');
    Route::get('/api/recent', [SignalController::class, 'getRecentSignals'])->name('signals.recent');
});

// API para dados em tempo real
Route::get('/api/asset/{assetId}/data', [DashboardController::class, 'getAssetData'])->name('api.asset.data');

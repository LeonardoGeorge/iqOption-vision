<?php
// routes/web.php

use Illuminate\Support\Facades\Route;

// Rota simples para teste
Route::get('/', function () {
    return view('welcome');
});

// Rota do dashboard SEM Livewire (solução temporária)
Route::get('/dashboard', function () {
    return view('dashboard-simple');
});

// Ou use a view dashboard original sem componentes Livewire
Route::get('/dashboard-original', function () {
    return view('dashboard');
});

/* use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\SignalController;

Route::get('/', function () {
    return view('welcome');
});


// Rota principal do dashboard
Route::get('/dashboard', DashboardTrading::class)->name('dashboard');

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
*/
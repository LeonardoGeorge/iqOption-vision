<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Signal;
use App\Services\IQOptionService;
use App\Services\TrendAnalyzer;

class DashboardController extends Controller
{
    public function index()
    {
        $assets = Asset::active()->get();
        $recentSignals = Signal::with('asset')
            ->recent(24)
            ->orderBy('timestamp', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact('assets', 'recentSignals'));
    }

    public function getAssetData($assetId)
    {
        $asset = Asset::findOrFail($assetId);
        $iqOptionService = new IQOptionService();
        $trendAnalyzer = new TrendAnalyzer();

        $priceData = $iqOptionService->getPriceData($asset->symbol, 5, 100);
        $analysis = $trendAnalyzer->analyzePriceData($priceData['data']);

        return response()->json([
            'asset' => $asset,
            'price_data' => $priceData['data'],
            'analysis' => $analysis
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Services\IQOptionService;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::all();
        return view('assets.index', compact('assets'));
    }

    public function syncAssets()
    {
        $iqOptionService = new IQOptionService();
        $availableAssets = $iqOptionService->getAvailableAssets();

        foreach ($availableAssets as $assetData) {
            Asset::updateOrCreate(
                ['symbol' => $assetData['symbol']],
                [
                    'name' => $assetData['name'],
                    'type' => $assetData['type'],
                    'active' => true
                ]
            );
        }

        return redirect()->back()->with('success', 'Ativos sincronizados com sucesso!');
    }

    public function toggleActive($id)
    {
        $asset = Asset::findOrFail($id);
        $asset->update(['active' => !$asset->active]);

        return redirect()->back()->with('success', 'Status do ativo atualizado!');
    }
}

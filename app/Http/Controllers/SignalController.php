<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Signal;
use App\Models\Asset;

class SignalController extends Controller
{
    public function index()
    {
        $signals = Signal::with('asset')
            ->orderBy('timestamp', 'desc')
            ->paginate(20);

        return view('signals.index', compact('signals'));
    }

    public function show($id)
    {
        $signal = Signal::with('asset')->findOrFail($id);
        return view('signals.show', compact('signal'));
    }

    public function getRecentSignals()
    {
        $signals = Signal::with('asset')
            ->recent(6)
            ->highConfidence()
            ->orderBy('confidence', 'desc')
            ->get();

        return response()->json($signals);
    }
}

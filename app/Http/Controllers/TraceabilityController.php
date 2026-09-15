<?php

namespace App\Http\Controllers;

use App\Services\TraceabilityService;
use Illuminate\Http\Request;

class TraceabilityController extends Controller
{
    /**
     * Display the traceability search page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('traceability.index');
    }

    /**
     * Search for batch/production code across all modules
     *
     * @param Request $request
     * @param TraceabilityService $service
     * @return \Illuminate\View\View
     */
    public function search(Request $request, TraceabilityService $service)
    {
        $request->validate([
            'production_code' => 'required|string|min:2',
        ], [
            'production_code.required' => 'Kode produksi wajib diisi.',
            'production_code.min' => 'Kode produksi minimal 2 karakter.',
        ]);

        $productionCode = $request->production_code;
        $results = $service->traceByBatch($productionCode);

        return view('traceability.index', [
            'results' => $results,
            'production_code' => $productionCode,
        ]);
    }
}

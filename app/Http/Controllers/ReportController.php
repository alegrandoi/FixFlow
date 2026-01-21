<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function __construct(private ReportService $reportService)
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $statistics = $this->reportService->getGeneralStatistics();
        
        return view('reports.dashboard', compact('statistics'));
    }

    public function assetReport(Asset $asset, Request $request)
    {
        $validated = $request->validate([
            'range' => 'nullable|in:monthly,yearly,all',
        ]);
        
        $range = $validated['range'] ?? 'all';
        
        $pdf = $this->reportService->generateAssetReport($asset->id, $range);
        
        return $pdf->stream("asset-{$asset->id}-report.pdf");
    }

    public function monthlyCosts(Request $request)
    {
        $validated = $request->validate([
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2000|max:2100',
        ]);
        
        $month = $validated['month'] ?? null;
        $year = $validated['year'] ?? null;
        
        $costs = $this->reportService->getMonthlyCosts($month, $year);
        
        return view('reports.monthly-costs', compact('costs', 'month', 'year'));
    }

    public function exportMonthlyCosts(Request $request)
    {
        $validated = $request->validate([
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2000|max:2100',
        ]);
        
        $month = $validated['month'] ?? null;
        $year = $validated['year'] ?? null;
        
        $costs = $this->reportService->getMonthlyCosts($month, $year);
        
        $filename = 'monthly-costs-' . ($month ?? 'all') . '-' . ($year ?? date('Y')) . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($costs) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Asset Name', 'Preventive Cost', 'Corrective Cost', 'Total Cost']);
            
            foreach ($costs as $cost) {
                fputcsv($file, [
                    $cost->asset_name,
                    number_format($cost->preventive_cost, 2),
                    number_format($cost->corrective_cost, 2),
                    number_format($cost->total_cost, 2),
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}

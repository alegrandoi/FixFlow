<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Maintenance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Generate a PDF report for an asset's maintenance history.
     *
     * @param int $assetId The asset ID
     * @param string $range 'monthly' | 'yearly' | 'all'
     * @return \Barryvdh\DomPDF\PDF
     * @throws \Exception If the asset does not exist
     */
    public function generateAssetReport(int $assetId, string $range = 'all')
    {
        $asset = Asset::with(['maintenances' => function ($query) use ($range) {
            if ($range === 'monthly') {
                $query->where('performed_at', '>=', now()->subMonth());
            } elseif ($range === 'yearly') {
                $query->where('performed_at', '>=', now()->subYear());
            }
            $query->orderBy('performed_at', 'desc');
        }])->findOrFail($assetId);

        $totalCost = $asset->maintenances->sum('cost');
        $preventiveMaintenance = $asset->maintenances->where('type', 'preventive')->count();
        $correctiveMaintenance = $asset->maintenances->where('type', 'corrective')->count();

        $data = [
            'asset' => $asset,
            'maintenances' => $asset->maintenances,
            'totalCost' => $totalCost,
            'preventiveMaintenance' => $preventiveMaintenance,
            'correctiveMaintenance' => $correctiveMaintenance,
            'range' => $range,
        ];

        return Pdf::loadView('reports.asset-report', $data);
    }

    /**
     * Get monthly maintenance costs grouped by asset.
     *
     * @param int|null $month Month (1-12) or null for all months
     * @param int|null $year Year or null for all years
     * @return Collection
     */
    public function getMonthlyCosts(?int $month = null, ?int $year = null): Collection
    {
        $query = Maintenance::with('asset');

        if ($year !== null) {
            $query->whereYear('performed_at', $year);
        }

        if ($month !== null) {
            $query->whereMonth('performed_at', $month);
        }

        return $query->get()
            ->groupBy('asset.name')
            ->map(function ($maintenances, $assetName) {
                return (object) [
                    'asset_name' => $assetName,
                    'total_cost' => $maintenances->sum('cost'),
                    'preventive_cost' => $maintenances->where('type', 'preventive')->sum('cost'),
                    'corrective_cost' => $maintenances->where('type', 'corrective')->sum('cost'),
                    'count' => $maintenances->count(),
                ];
            })
            ->values();
    }

    /**
     * Get general statistics for the system.
     *
     * @return array
     */
    public function getGeneralStatistics(): array
    {
        $totalAssets = Asset::count();
        $activeAssets = Asset::where('status', 'active')->count();
        $brokenAssets = Asset::where('status', 'broken')->count();
        $totalMaintenances = Maintenance::count();
        $totalCost = Maintenance::sum('cost');
        
        $maintenancesByType = Maintenance::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        return [
            'total_assets' => $totalAssets,
            'active_assets' => $activeAssets,
            'broken_assets' => $brokenAssets,
            'total_maintenances' => $totalMaintenances,
            'total_cost' => $totalCost,
            'preventive_maintenances' => $maintenancesByType['preventive'] ?? 0,
            'corrective_maintenances' => $maintenancesByType['corrective'] ?? 0,
        ];
    }
}

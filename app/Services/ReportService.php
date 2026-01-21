<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Maintenance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Genera un informe PDF del historial de un activo.
     *
     * @param int $assetId El ID del activo
     * @param string $range 'monthly' | 'yearly' | 'all'
     * @return \Barryvdh\DomPDF\PDF
     * @throws \Exception Si el activo no existe
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
     * Genera un informe de costos mensuales por tipo de activo.
     *
     * @param int|null $month
     * @param int|null $year
     * @return Collection
     */
    public function getMonthlyCosts(?int $month = null, ?int $year = null): Collection
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        return Maintenance::with('asset')
            ->whereYear('performed_at', $year)
            ->whereMonth('performed_at', $month)
            ->get()
            ->groupBy('asset.name')
            ->map(function ($maintenances) {
                return [
                    'total_cost' => $maintenances->sum('cost'),
                    'preventive_cost' => $maintenances->where('type', 'preventive')->sum('cost'),
                    'corrective_cost' => $maintenances->where('type', 'corrective')->sum('cost'),
                    'count' => $maintenances->count(),
                ];
            });
    }

    /**
     * Obtiene estadísticas generales del sistema.
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
            'maintenances_by_type' => $maintenancesByType,
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Enums\AssetStatus;
use App\Enums\MaintenanceType;
use App\Http\Requests\StoreMaintenanceRequest;
use App\Models\Asset;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Maintenance::class, 'maintenance');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Maintenance::with(['asset', 'technician']);

        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('performed_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('performed_at', '<=', $request->date_to);
        }

        $maintenances = $query->orderBy('performed_at', 'desc')->paginate(15);
        $assets = Asset::orderBy('name')->get();
        $types = MaintenanceType::cases();

        return view('maintenances.index', compact('maintenances', 'assets', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assets = Asset::orderBy('name')->get();
        $types = MaintenanceType::cases();
        
        return view('maintenances.create', compact('assets', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaintenanceRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $maintenance = Maintenance::create($data);

        if ($request->filled('update_asset_status')) {
            $asset = Asset::find($data['asset_id']);
            $asset->update(['status' => $request->update_asset_status]);
        }

        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Maintenance $maintenance)
    {
        $maintenance->load(['asset', 'technician']);
        
        return view('maintenances.show', compact('maintenance'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();

        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance record deleted successfully.');
    }
}


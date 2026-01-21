<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Report - {{ $asset->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 14px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 5px 10px 5px 0;
            width: 30%;
        }
        .info-value {
            display: table-cell;
            padding: 5px 0;
        }
        .stats-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            width: 33.33%;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 11px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table thead {
            background-color: #f5f5f5;
        }
        table th {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-preventive {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        .badge-corrective {
            background-color: #fff3e0;
            color: #f57c00;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Asset Maintenance Report</h1>
        <p>{{ $asset->name }} ({{ ucfirst($range) }} Report)</p>
        <p>Generated on {{ date('F d, Y') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Asset Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value">{{ $asset->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Serial Number:</div>
                <div class="info-value">{{ $asset->serial_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">{{ ucfirst($asset->status->value) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Location:</div>
                <div class="info-value">{{ $asset->location }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Purchase Date:</div>
                <div class="info-value">{{ $asset->purchase_date->format('F d, Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Purchase Cost:</div>
                <div class="info-value">${{ number_format($asset->purchase_cost, 2) }}</div>
            </div>
            @if($asset->description)
            <div class="info-row">
                <div class="info-label">Description:</div>
                <div class="info-value">{{ $asset->description }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">Maintenance Summary</div>
        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-value">{{ $maintenances->count() }}</div>
                <div class="stat-label">Total Maintenances</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $preventiveMaintenance }}</div>
                <div class="stat-label">Preventive</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $correctiveMaintenance }}</div>
                <div class="stat-label">Corrective</div>
            </div>
        </div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Total Maintenance Cost:</div>
                <div class="info-value" style="font-weight: bold; font-size: 14px;">${{ number_format($totalCost, 2) }}</div>
            </div>
        </div>
    </div>

    @if($maintenances->isNotEmpty())
    <div class="section">
        <div class="section-title">Maintenance History</div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Cost</th>
                    <th>Technician</th>
                </tr>
            </thead>
            <tbody>
                @foreach($maintenances as $maintenance)
                <tr>
                    <td>{{ $maintenance->performed_at->format('M d, Y') }}</td>
                    <td>
                        <span class="badge badge-{{ $maintenance->type }}">
                            {{ ucfirst($maintenance->type) }}
                        </span>
                    </td>
                    <td>{{ $maintenance->description }}</td>
                    <td>${{ number_format($maintenance->cost, 2) }}</td>
                    <td>{{ $maintenance->performed_by ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="section">
        <div class="section-title">Maintenance History</div>
        <p>No maintenance records found for the selected period.</p>
    </div>
    @endif

    <div class="footer">
        <p>This report was generated by FixFlow Asset Management System</p>
        <p>Report Period: {{ ucfirst($range) }} | Generated: {{ date('F d, Y H:i:s') }}</p>
    </div>
</body>
</html>

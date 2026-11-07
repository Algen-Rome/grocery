<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Grocery Inventory Status Report</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.5;
        }

        .header {
            margin-bottom: 25px;
            border-bottom: 2px solid #065f46;
            padding-bottom: 15px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
            margin: 0;
        }

        .subtitle {
            color: #6b7280;
            font-size: 10px;
            margin-top: 5px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .meta-box {
            margin-bottom: 25px;
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .meta-grid {
            width: 100%;
        }

        .meta-col {
            width: 25%;
            text-align: left;
        }

        .meta-label {
            font-size: 9px;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: bold;
        }

        .meta-value {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 2px;
        }

        .meta-value.danger {
            color: #dc2626;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #cbd5e1;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 11px;
            color: #334155;
        }

        tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .badge {
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 4px;
        }

        .badge-ok {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-low {
            background-color: #ffedd5;
            color: #9a3412;
        }

        .badge-out {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1 class="title">Inventory Status Report</h1>
        <div class="subtitle">Generated on: {{ $generatedAt }}</div>
    </div>

    <!-- Summary Statistics Grid Ribbon View -->
    <div class="meta-box">
        <table class="meta-grid">
            <tr>
                <td class="meta-col" style="border:0; padding:0;">
                    <div class="meta-label">Total Registered Items</div>
                    <div class="meta-value">{{ number_format($totalProducts) }}</div>
                </td>
                <td class="meta-col" style="border:0; padding:0;">
                    <div class="meta-label">Low Stock Alerts</div>
                    <div class="meta-value {{ $lowStockItems > 0 ? 'danger' : '' }}">{{ $lowStockItems }}</div>
                </td>
                <td class="meta-col" style="border:0; padding:0;">
                    <div class="meta-label">Out of Stock Items</div>
                    <div class="meta-value {{ $outOfStock > 0 ? 'danger' : '' }}">{{ $outOfStock }}</div>
                </td>
                <td class="meta-col" style="border:0; padding:0;">
                    <div class="meta-label">Total Warehouse Value</div>
                    <div class="meta-value" style="color: #059669;">
                        <span
                            style="font-family: 'DejaVu Sans', sans-serif;">&#8369;</span>{{ number_format(str_replace(['₱', ','], '', $stockValueDisplay), 2) }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Detailed Ledger Data List Grid Sheet -->
    <h3>Product Registry Inventory Itemization Ledger</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 40%;">PRODUCT NAME</th>
                <th style="width: 15%;">PRICE</th>
                <th style="width: 15%;">STOCK UNIT QTY</th>
                <th style="width: 15%;">REORDER LAYER</th>
                <th style="width: 15%;">STATUS INDICATOR</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventoryItems as $item)
                <tr>
                    <td style="font-weight: bold; color: #0f172a;">{{ $item->name }}</td>
                    <td>
                        <span
                            style="font-family: 'DejaVu Sans', sans-serif;">&#8369;</span>{{ number_format($item->price, 2) }}
                    </td>
                    <td>{{ number_format($item->stock_count) }} units</td>
                    <td>{{ number_format($item->reorder_level) }} units</td>
                    <td>
                        @if($item->stock_count <= 0)
                            <span class="badge badge-out">OUT OF STOCK</span>
                        @elseif($item->stock_count <= $item->reorder_level)
                            <span class="badge badge-low">LOW STOCK</span>
                        @else
                            <span class="badge badge-ok">IN STOCK</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
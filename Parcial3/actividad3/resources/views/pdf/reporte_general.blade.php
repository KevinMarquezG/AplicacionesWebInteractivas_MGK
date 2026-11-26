<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte General</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 20px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #34495e; color: white; padding: 8px; text-align: left; }
        td { padding: 8px; border: 1px solid #ddd; }
        .text-right { text-align: right; }
        .total-row { background-color: #ecf0f1; font-weight: bold; }
    </style>
</head>
<body>
    <h2>REPORTE GENERAL DE INGRESOS</h2>
    <div class="subtitle">Generado el {{ date('d/m/Y H:i') }}</div>

    <table>
        <thead>
            <tr>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Concepto</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recibos as $r)
                <tr>
                    <td>{{ $r->folio }}</td>
                    <td>{{ $r->created_at->format('d/m/Y') }}</td>
                    <td>{{ $r->cliente }}</td>
                    <td>{{ $r->concepto }}</td>
                    <td class="text-right">${{ number_format($r->total, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL ACUMULADO:</td>
                <td class="text-right">${{ number_format($granTotal, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $recibo->folio }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #333; }
        .header { width: 100%; border-bottom: 2px solid #2c3e50; padding-bottom: 10px; margin-bottom: 25px; }
        .title { font-size: 22px; font-weight: bold; color: #2c3e50; }
        .folio { font-size: 15px; color: #c0392b; font-weight: bold; }
        .info-box { background: #f8f9fa; padding: 15px; border: 1px solid #ddd; margin-bottom: 25px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #2c3e50; color: white; padding: 10px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        .text-right { text-align: right; }
        .total-box { text-align: right; font-size: 16px; font-weight: bold; margin-top: 10px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 11px; color: #777; border-top: 1px solid #ddd; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <table style="margin: 0;">
            <tr>
                <td style="border: none; padding: 0;">
                    <div class="title">COMPROBANTE DE SERVICIO</div>
                    <div>Soluciones Digitales S.A.</div>
                </td>
                <td style="border: none; padding: 0;" class="text-right">
                    <div class="folio">FOLIO: {{ $recibo->folio }}</div>
                    <div>Fecha: {{ $recibo->created_at->format('d/m/Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="info-box">
        <strong>Datos del Cliente:</strong><br>
        Nombre / Razón Social: {{ $recibo->cliente }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Cant.</th>
                <th>Descripción del Concepto</th>
                <th class="text-right">Precio Unitario</th>
                <th class="text-right">Importe</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $recibo->cantidad }}</td>
                <td>{{ $recibo->concepto }}</td>
                <td class="text-right">${{ number_format($recibo->precio_unitario, 2) }}</td>
                <td class="text-right">${{ number_format($recibo->total, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total-box">
        TOTAL A PAGAR: ${{ number_format($recibo->total, 2) }} MXN
    </div>

    <div class="footer">
        Documento generado automáticamente el {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Recibos PDF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Gestor de Recibos y Reportes PDF</h2>
        <a href="{{ route('recibos.general') }}" class="btn btn-danger">
            Descargar Reporte General (PDF)
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <!-- Formulario -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Nuevo Recibo</div>
                <div class="card-body">
                    <form action="{{ route('recibos.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Cliente</label>
                            <input type="text" name="cliente" class="form-control" required placeholder="Ej. Empresa ABC">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Concepto / Servicio</label>
                            <input type="text" name="concepto" class="form-control" required placeholder="Ej. Mantenimiento Web">
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Cantidad</label>
                                <input type="number" name="cantidad" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Precio Unitario</label>
                                <input type="number" step="0.01" name="precio_unitario" class="form-control" required placeholder="0.00">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Guardar Registro</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de Registros -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">Historial de Recibos</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Folio</th>
                                <th>Cliente</th>
                                <th>Concepto</th>
                                <th>Total</th>
                                <th class="text-end">Acciones PDF</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recibos as $recibo)
                                <tr>
                                    <td class="fw-bold">{{ $recibo->folio }}</td>
                                    <td>{{ $recibo->cliente }}</td>
                                    <td>{{ $recibo->concepto }} ({{ $recibo->cantidad }})</td>
                                    <td>${{ number_format($recibo->total, 2) }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('recibos.ver', $recibo) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            Ver
                                        </a>
                                        <a href="{{ route('recibos.descargar', $recibo) }}" class="btn btn-sm btn-success">
                                            Descargar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No hay recibos registrados aún.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
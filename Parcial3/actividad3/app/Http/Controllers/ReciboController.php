<?php

namespace App\Http\Controllers;

use App\Models\Recibo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReciboController extends Controller
{
    // Vista principal con formulario y listado
    public function index()
    {
        $recibos = Recibo::latest()->get();
        return view('recibos.index', compact('recibos'));
    }

    // Guardar nuevo registro
    public function store(Request $request)
    {
        $request->validate([
            'cliente' => 'required|string|max:255',
            'concepto' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
        ]);

        $total = $request->cantidad * $request->precio_unitario;
        $folio = 'REC-' . str_pad(Recibo::count() + 1, 4, '0', STR_PAD_LEFT);

        Recibo::create([
            'folio' => $folio,
            'cliente' => $request->cliente,
            'concepto' => $request->concepto,
            'cantidad' => $request->cantidad,
            'precio_unitario' => $request->precio_unitario,
            'total' => $total,
        ]);

        return redirect()->route('recibos.index')->with('success', 'Recibo registrado correctamente.');
    }

    // Visualizar PDF individual en el navegador
    public function verPdf(Recibo $recibo)
    {
        $pdf = Pdf::loadView('pdf.recibo_individual', compact('recibo'));
        return $pdf->stream($recibo->folio . '.pdf');
    }

    // Descargar PDF individual
    public function descargarPdf(Recibo $recibo)
    {
        $pdf = Pdf::loadView('pdf.recibo_individual', compact('recibo'));
        return $pdf->download($recibo->folio . '.pdf');
    }

    // Descargar reporte con todos los registros en tabla
    public function reporteGeneral()
    {
        $recibos = Recibo::all();
        $granTotal = $recibos->sum('total');
        
        $pdf = Pdf::loadView('pdf.reporte_general', compact('recibos', 'granTotal'))
                  ->setPaper('letter', 'portrait');

        return $pdf->download('reporte_general_' . date('Y-m-d') . '.pdf');
    }
}
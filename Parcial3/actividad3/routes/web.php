<?php

use App\Http\Controllers\ReciboController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ReciboController::class, 'index'])->name('recibos.index');
Route::post('/recibos', [ReciboController::class, 'store'])->name('recibos.store');
Route::get('/recibos/{recibo}/ver', [ReciboController::class, 'verPdf'])->name('recibos.ver');
Route::get('/recibos/{recibo}/descargar', [ReciboController::class, 'descargarPdf'])->name('recibos.descargar');
Route::get('/recibos/reporte-general', [ReciboController::class, 'reporteGeneral'])->name('recibos.general');
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    protected $fillable = [
        'folio',
        'cliente',
        'concepto',
        'cantidad',
        'precio_unitario',
        'total',
    ];
}
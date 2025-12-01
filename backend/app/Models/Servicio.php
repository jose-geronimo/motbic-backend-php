<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Servicio extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'cliente_id', 'inventario_id', 'venta_id', 'tipo_servicio', 'fecha_programada', 'fecha_realizada', 'estado', 'notas', 'costo'
    ];

    protected $casts = [
        'fecha_programada' => 'date',
        'fecha_realizada' => 'date',
        'costo' => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}

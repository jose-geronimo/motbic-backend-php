<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Inventario extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventario';

    protected $fillable = [
        'modelo_id', 'color', 'serie', 'motor', 'vin', 'estado'
    ];

    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }
}

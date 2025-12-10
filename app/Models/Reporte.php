<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reporte extends Model
{
    use HasFactory;

    protected $table = 'reporte';
    protected $primaryKey = 'idReporte';

    protected $fillable = [
        'idPedido',
        'idUsuario',
        'titulo',
        'descripcion',
        'tipo',
        'fechaGeneracion',
        'estado'
    ];

    protected $casts = [
        'fechaGeneracion' => 'datetime'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'idPedido', 'idPedido');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedido';
    protected $primaryKey = 'idPedido';

    protected $fillable = [
        'idUsuario',
        'codigo',
        'fechaPedido',
        'tipoPago',
        'total',
        'estado'
    ];

    protected $casts = [
        'fechaPedido' => 'datetime',
        'total' => 'float'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'idPedido', 'idPedido');
    }

    public function reportes()
    {
        return $this->hasMany(Reporte::class, 'idPedido', 'idPedido');
    }
}

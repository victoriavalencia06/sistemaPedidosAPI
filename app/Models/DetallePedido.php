<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetallePedido extends Model
{
    use HasFactory;

    protected $table = 'detallePedido';
    protected $primaryKey = 'idDetallePedido';

    protected $fillable = [
        'idPedido',
        'idProducto',
        'cantidad',
        'subtotal'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'idPedido', 'idPedido');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idProducto', 'idProducto');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detallePedido', function (Blueprint $table) {
            $table->id('idDetallePedido');
            $table->unsignedBigInteger('idPedido');
            $table->unsignedBigInteger('idProducto');
            $table->integer('cantidad');
            $table->decimal('subtotal', 8, 2);
            $table->timestamps();

            $table->foreign('idPedido')
                ->references('idPedido')->on('pedido')
                ->onDelete('cascade');

            $table->foreign('idProducto')
                ->references('idProducto')->on('producto')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detallePedido');
    }
};

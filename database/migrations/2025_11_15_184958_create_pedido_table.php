<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido', function (Blueprint $table) {
            $table->id('idPedido');
            $table->unsignedBigInteger('idUsuario');
            $table->string('codigo', 30);
            $table->dateTime('fechaPedido');
            $table->string('tipoPago', 30)->nullable();
            $table->decimal('total', 8, 2)->default(0);
            $table->string('estado', 20)->default('Pendiente');
            $table->timestamps();

            $table->foreign('idUsuario')
                ->references('idUsuario')->on('usuario')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reporte', function (Blueprint $table) {
            $table->id('idReporte');
            $table->unsignedBigInteger('idUsuario');
            $table->unsignedBigInteger('idPedido')->nullable();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('tipo', 50);
            $table->dateTime('fechaGeneracion');
            $table->timestamps();

            $table->foreign('idUsuario')
                ->references('idUsuario')->on('usuario')
                ->onDelete('cascade');

            $table->foreign('idPedido')
                ->references('idPedido')->on('pedido')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reporte');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->id('idProducto');
            $table->unsignedBigInteger('idCategoria');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 8, 2);
            $table->integer('stock')->default(0);
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('idCategoria')
                ->references('idCategoria')->on('categoria')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};

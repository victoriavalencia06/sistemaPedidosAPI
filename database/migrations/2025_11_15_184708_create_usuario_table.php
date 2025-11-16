<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('idUsuario');
            $table->unsignedBigInteger('idRol');
            $table->string('nombre', 100);
            $table->string('email', 150)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('estado')->default(true);

            $table->rememberToken();
            $table->timestamps();

            // Foreign Key
            $table->foreign('idRol')
                  ->references('idRol')->on('rol')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};

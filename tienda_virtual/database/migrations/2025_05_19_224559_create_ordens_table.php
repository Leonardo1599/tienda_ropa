<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdensTable extends Migration
{
    public function up()
    {
        Schema::create('ordens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pendiente', 'procesando', 'completado', 'cancelado'])->default('pendiente');
            $table->string('razon_social')->nullable();
            $table->string('ruc', 15)->nullable();
            $table->string('nombre')->nullable();
            $table->string('dni', 15)->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('ordens');
    }
}

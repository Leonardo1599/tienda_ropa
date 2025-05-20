<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdensTable extends Migration {
    public function up() {
        Schema::create('ordens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pendiente', 'procesando', 'completado', 'cancelado'])->default('pendiente');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('ordens');
    }
}

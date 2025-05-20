<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pasarelas', function (Blueprint $table) {
            $table->id();
            $table->string('yape_numero')->nullable();
            $table->string('yape_qr')->nullable();
            $table->string('plin_numero')->nullable();
            $table->string('plin_qr')->nullable();
            $table->string('cuenta_transferencia')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasarelas');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('ordens', function (Blueprint $table) {
            $table->boolean('comprobante_validado')->nullable()->after('comprobante_pago');
            $table->string('comentario_admin')->nullable()->after('comprobante_validado');
        });
    }
    public function down()
    {
        Schema::table('ordens', function (Blueprint $table) {
            $table->dropColumn(['comprobante_validado', 'comentario_admin']);
        });
    }
};

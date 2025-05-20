<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('ordens', function (Blueprint $table) {
            $table->string('comprobante_pago')->nullable()->after('status');
        });
    }
    public function down()
    {
        Schema::table('ordens', function (Blueprint $table) {
            $table->dropColumn('comprobante_pago');
        });
    }
};

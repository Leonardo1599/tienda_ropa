<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftdeletesToProductosAndOrdens extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('ordens', function (Blueprint $table) {
            $table->softDeletes();
        });
    }
    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('ordens', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}

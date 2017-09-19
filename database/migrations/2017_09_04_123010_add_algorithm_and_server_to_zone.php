<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlgorithmAndServerToZone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zone', function (Blueprint $table) {
            $table->string('server')->nullable()->default(null);
            $table->binary('tsigalgo')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zone', function (Blueprint $table) {
            $table->dropColumn('server');
            $table->dropColumn('tsigalgo');
        });
    }
}

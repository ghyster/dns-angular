<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zone', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->binary('tsigname')->nullable()->default(null);
            $table->binary('tsigkey')->nullable()->default(null);
            $table->boolean('reverse')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('zone');
    }
}

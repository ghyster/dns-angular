<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_zone', function (Blueprint $table) {
          $table->integer('user')->unsigned();
          $table->integer('zone')->unsigned();

          $table->foreign('user')->references('id')->on('user')->onDelete('cascade');
          $table->foreign('zone')->references('id')->on('zone')->onDelete('cascade');

          $table->primary(['user', 'zone']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_zone');
    }
}

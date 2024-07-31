<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourEntriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('point', 255);
            $table->string('route', 255);
            $table->string('objectives', 255);
            $table->string('issues', 255);
            $table->string('contactperson', 255);
            $table->string('hq', 100);
            $table->text('remarks');
            $table->text('feedback');
            $table->integer('status');
            $table->integer('approval');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tour_entries');
    }
}

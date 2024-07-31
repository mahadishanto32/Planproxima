<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKRASTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('k_r_a_s', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kra_name', 255);
            $table->integer('dept_id')->unsigned();
            $table->integer('year');
            $table->integer('kra_weight');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('dept_id')->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('k_r_a_s');
    }
}

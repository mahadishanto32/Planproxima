<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKPISTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('k_p_i_s', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dept_id')->unsigned();
            $table->integer('kra_id')->unsigned();
            $table->string('kpi_name', 255);
            $table->integer('kpi_weight');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('dept_id')->references('id')->on('departments');
            $table->foreign('kra_id')->references('id')->on('k_r_a_s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('k_p_i_s');
    }
}

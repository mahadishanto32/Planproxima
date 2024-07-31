<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMOSTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_o_s', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dept_id')->unsigned();
            $table->integer('kra_id')->unsigned();
            $table->integer('kpi_id')->unsigned();
            $table->string('mos_name', 255);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('dept_id')->references('id')->on('departments');
            $table->foreign('kra_id')->references('id')->on('k_r_a_s');
            $table->foreign('kpi_id')->references('id')->on('k_p_i_s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('m_o_s');
    }
}

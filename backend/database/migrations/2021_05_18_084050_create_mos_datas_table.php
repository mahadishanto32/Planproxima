<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMosDatasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mos_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mos_id')->unsigned();
            $table->integer('type');
            $table->float('january');
            $table->float('february');
            $table->float('march');
            $table->float('april');
            $table->float('may');
            $table->float('june');
            $table->float('july');
            $table->float('august');
            $table->float('september');
            $table->float('october');
            $table->float('november');
            $table->float('december');
            $table->integer('dept_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('mos_id')->references('id')->on('m_o_s');
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
        Schema::drop('mos_datas');
    }
}

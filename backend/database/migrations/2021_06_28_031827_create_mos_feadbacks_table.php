<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMosFeadbacksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mos_feadbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mos_id');
            $table->integer('user_id');
            $table->text('msg');
            $table->string('month',100);
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mos_feadbacks');
    }
}

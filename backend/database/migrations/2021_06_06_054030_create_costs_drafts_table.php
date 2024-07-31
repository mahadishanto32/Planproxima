<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostsDraftsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('costs_drafts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('factory_code');
            $table->integer('cost');
            $table->string('remarks');
            $table->string('cost_center');
            $table->string('error_note');
            $table->string('gl_code');
            $table->date('data');
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
        Schema::drop('costs_drafts');
    }
}

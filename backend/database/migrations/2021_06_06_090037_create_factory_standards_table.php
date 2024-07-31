<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactoryStandardsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factory_standards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('year');
            $table->string('type');
            $table->string('cost_center');
            $table->string('gl_code');
            $table->string('gl_text');
            $table->string('cost_amount');
            $table->string('cost_center_id');
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
        Schema::drop('factory_standards');
    }
}

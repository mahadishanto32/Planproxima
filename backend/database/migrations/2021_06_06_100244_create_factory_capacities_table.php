<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactoryCapacitiesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factory_capacities', function (Blueprint $table) {
            $table->increments('id');
            $table->double('jan');
            $table->double('feb');
            $table->double('mar');
            $table->double('apr');
            $table->double('may');
            $table->double('jun');
            $table->double('jul');
            $table->double('aug');
            $table->double('sep');
            $table->double('oct');
            $table->double('nov');
            $table->double('dec');
            $table->integer('summary_group_id');
            $table->string('year');
            $table->string('type');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->double('total_capacity');
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
        Schema::drop('factory_capacities');
    }
}

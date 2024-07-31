<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionTargetsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_targets', function (Blueprint $table) {
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
            $table->integer('summary_group_id');
            $table->string('year');
            $table->string('type');
            $table->string('material_code');
            $table->double('production_target');
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::drop('production_targets');
    }
}

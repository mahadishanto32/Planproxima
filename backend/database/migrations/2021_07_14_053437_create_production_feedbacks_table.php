<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionFeedbacksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_feedbacks', function (Blueprint $table) {
            $table->id('id');
            $table->integer('factory_id');
            $table->integer('summary_group_id');
            $table->string('production_type');
            $table->integer('section');
            $table->string('section_name');
            $table->string('comments');
            $table->string('type');
            $table->date('start_date');
            $table->date('end_date');
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
        Schema::drop('production_feedbacks');
    }
}

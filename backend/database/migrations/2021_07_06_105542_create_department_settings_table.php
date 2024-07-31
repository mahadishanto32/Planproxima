<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentSettingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_settings', function (Blueprint $table) {
            $table->id('id');
            $table->string('jan');
            $table->string('feb');
            $table->string('mar');
            $table->string('apr');
            $table->string('may');
            $table->string('jun');
            $table->string('jul');
            $table->string('aug');
            $table->string('sep');
            $table->string('oct');
            $table->string('nov');
            $table->string('dec');
            $table->string('trype');
            $table->integer('dept_id');
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
        Schema::drop('department_settings');
    }
}

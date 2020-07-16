<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->morphs('classable'); //facilitator school
            $table->unsignedBigInteger('curriculum_id')->nullable();
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->unsignedBigInteger('grade_system_id')->nullable();
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            
            $table->foreign('curriculum_id')->references('id')->on('curricula')->cascadeOnDelete();
            // $table->foreign('grade_id')->references('id')->on('grades')->cascadeOnDelete();
            // $table->foreign('academic_year_id')->references('id')->on('academic_year')->cascadeOnDelete();
            // $table->foreign('grade_system_id')->references('id')->on('grade_systems')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_models');
    }
}

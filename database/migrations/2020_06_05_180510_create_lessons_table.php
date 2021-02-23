<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('ownedby'); //school facilitator professional group collaboration
            $table->morphs('addedby'); //delivered by collaboration facilitator professional
            $table->nullableMorphs('lessonable'); //post 
            $table->unsignedBigInteger('previous_lesson_id')->nullable();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('ageGroup')->nullable();
            $table->enum('state',['PENDING','DECLINED','ACCEPTED','DELETED'])->nullable();
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('previous_lesson_id')->references('id')->on('lessons')->cascadeOnDelete();
            // $table->foreign('structure_id')->references('id')->on('curriculum_structures')->cascadeOnDelete();
            // $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            $table->foreign('class_id')->references('id')->on('class_models')->cascadeOnDelete();
            // $table->foreign('class_section_id')->references('id')->on('class_sections')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lessons');
    }
}

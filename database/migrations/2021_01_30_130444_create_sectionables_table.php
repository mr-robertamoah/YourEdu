<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sectionables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section_id');
            $table->nullableMorphs('sectionable');
            $table->mediumInteger('lesson_number')->nullable();
            $table->timestamps();
            

            $table->foreign('section_id')->references('id')->on('course_sections')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sectionables');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurriculumDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curriculum_details', function (Blueprint $table) {
            $table->id(); // this is for things like the actual names of strands
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('curriculum_id');
            $table->unsignedBigInteger('curriculum_structure_id');
            $table->softDeletes();
            $table->timestamps();
            
            
            $table->foreign('subject_id')->references('id')->on('subjects')->cascadeOnDelete();
            $table->foreign('curriculum_id')->references('id')->on('curricula')->cascadeOnDelete();
            $table->foreign('curriculum_structure_id')->references('id')->on('curriculum_structures')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curriculum_details');
    }
}

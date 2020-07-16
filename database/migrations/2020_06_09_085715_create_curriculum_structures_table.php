<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurriculumStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curriculum_structures', function (Blueprint $table) {
            $table->id(); //for things like strands
            $table->string('name');
            $table->string('actual_name')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('curriculum_id');
            $table->unsignedBigInteger('structure_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            
            $table->foreign('curriculum_id')->references('id')->on('curricula')->cascadeOnDelete();
            $table->foreign('structure_id')->references('id')->on('curriculum_structures')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curriculum_structures');
    }
}

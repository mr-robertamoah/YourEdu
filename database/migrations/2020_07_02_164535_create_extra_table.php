<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('extracurriculum_id');
            $table->nullableMorphs('extracurriculumable'); // school class group learner facilitator professional collaboration
            $table->boolean('resource')->default(false);
            $table->enum('activity',['FACILITATE','TAKE','OFFER'])->nullable();
            $table->timestamps();


            $table->foreign('extracurriculum_id')->references('id')->on('extracurriculums')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extra');
    }
}

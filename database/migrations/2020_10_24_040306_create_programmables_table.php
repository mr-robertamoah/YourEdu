<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgrammablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programmables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id');
            $table->nullableMorphs('programmable'); //learner facilitator professional school
            $table->timestamps();


            $table->foreign('program_id')->references('id')->on('programs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programmables');
    }
}

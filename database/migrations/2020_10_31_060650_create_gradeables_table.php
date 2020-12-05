<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gradeables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grade_id');
            $table->nullableMorphs('gradeable');
            $table->timestamps();


            $table->foreign('grade_id')->references('id')->on('grades')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gradeables');
    }
}

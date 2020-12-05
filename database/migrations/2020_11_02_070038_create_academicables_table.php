<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academicables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academic_id');
            $table->nullableMorphs('academicable'); // class
            $table->timestamps();

            $table->foreign('academic_id')->references('id')->on('academic_years')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academicables');
    }
}

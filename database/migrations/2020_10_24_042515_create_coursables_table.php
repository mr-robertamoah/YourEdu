<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coursables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->nullableMorphs('coursable');
            $table->boolean('resource')->default(false);
            $table->enum('activity',['FACILITATE','TAKE','OFFER','TYPE'])->nullable();
            //type means course attached to course and resource means it is being usedby
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coursables');
    }
}

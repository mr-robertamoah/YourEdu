<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjectables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->nullableMorphs('subjectable');
            $table->enum('activity',['FACILITATE','TAKE','OFFER'])->nullable();
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('subjects')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subjectables');
    }
}

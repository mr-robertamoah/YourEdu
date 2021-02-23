<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->nullableMorphs('classable');
            $table->boolean('resource')->default(false);
            $table->unsignedBigInteger('subject_id')->nullable(); //used for lessons when structure is subjects
            $table->enum('activity',['FREE','INTRO'])->nullable();
            $table->timestamps();


            $table->foreign('class_id')->references('id')->on('class_models')->cascadeOnDelete();
            // $table->foreign('subject_id')->references('id')->on('subjects')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classables');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schoolables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->nullableMorphs('schoolable');
            $table->enum('type',['TRADITIONAL','VIRTUAL'])->nullable();
            $table->timestamps();


            $table->foreign('school_id')->references('id')->on('schools')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schoolables');
    }
}

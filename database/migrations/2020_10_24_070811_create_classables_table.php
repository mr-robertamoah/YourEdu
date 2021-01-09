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
            $table->timestamps();


            $table->foreign('class_id')->references('id')->on('class_models')->cascadeOnDelete();
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

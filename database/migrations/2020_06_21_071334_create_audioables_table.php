<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audioables', function (Blueprint $table) {
            $table->unsignedBigInteger('audio_id');
            $table->morphs('audioable'); //question answer profile comment lesson activity
            $table->enum('state',['PUBLIC','PRIVATE'])->default('PUBLIC');
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('audio_id')->references('id')->on('audio')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audioables');
    }
}

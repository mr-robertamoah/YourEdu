<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('words', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('read_id');
            $table->morphs('wordable'); //learner school facilitators professionals parents
            $table->string('word');
            $table->string('general')->nullable();
            $table->string('context')->nullable();
            $table->string('part_of_speech')->nullable();
            $table->string('use')->nullable();
            $table->softDeletes();
            $table->timestamps();

            
            $table->foreign('read_id')->references('id')->on('reads')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('words');
    }
}

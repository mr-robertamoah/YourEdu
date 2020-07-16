<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('read_id');
            $table->morphs('characterable'); //learner school facilitators professionals parents
            $table->string('character');
            $table->string('role')->nullable();
            $table->string('quote')->nullable();
            $table->string('action')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('characters');
    }
}

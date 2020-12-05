<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_tracks', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('for'); // school group
            $table->nullableMorphs('what'); // comment post answer
            $table->nullableMorphs('who'); // admin
            $table->string('action')->nullable(); // admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_tracks');
    }
}

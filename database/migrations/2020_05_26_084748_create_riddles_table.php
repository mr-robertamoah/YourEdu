<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiddlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riddles', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('addedby'); // learner facilitator professional parent school
            $table->nullableMorphs('authoredby'); // learner facilitator professional parent
            $table->morphs('riddleable'); // post lesson
            // $table->string('title'); 
            $table->string('author')->nullable();
            $table->text('riddle')->nullable();
            $table->timestamp('published')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('riddles');
    }
}

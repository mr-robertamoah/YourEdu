<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscussionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussions', function (Blueprint $table) {
            $table->id();
            $table->morphs('raisedby'); //learner parent professional
            $table->nullableMorphs('discussionfor'); // class group lesson course extracurriculum
            $table->string('title');
            $table->string('preamble');
            $table->boolean('restricted');
            $table->enum('type',['PUBLIC','PRIVATE']);
            $table->enum('allowed',['ALL','LEARNERS','PARENTS','FACILITATORS','PROFESSIONALS','SCHOOLS'])->default('ALL');
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
        Schema::dropIfExists('discussions');
    }
}

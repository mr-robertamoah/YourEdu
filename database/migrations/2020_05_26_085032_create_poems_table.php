<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poems', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('addedby'); // learner facilitator professional parent school
            $table->nullableMorphs('authoredby'); // learner facilitator professional parent
            $table->nullableMorphs('poemable');  //lesson post
            $table->string('title'); 
            $table->string('author')->nullable();
            $table->text('about')->nullable();
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
        Schema::dropIfExists('poems');
    }
}

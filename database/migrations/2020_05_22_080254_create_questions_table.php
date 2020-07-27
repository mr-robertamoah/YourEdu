<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('owned'); // facilitator professional school parent learner member group collaboration
            $table->nullableMorphs('questionedby'); // facilitator professional parent learner member
            $table->nullableMorphs('questionable'); // assessmentsection post discussion message
            $table->text('question');
            $table->enum('state',['PENDING','ANSWERED','COMPLETE'])->nullable();
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
        Schema::dropIfExists('questions');
    }
}

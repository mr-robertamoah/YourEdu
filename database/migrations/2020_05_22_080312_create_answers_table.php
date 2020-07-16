<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('answerable'); // learner facilitator professional 
            $table->unsignedBigInteger('work_id')->nullable();
            $table->unsignedBigInteger('question_id')->nullable();
            $table->nullableMorphs('answerfor'); // riddle
            $table->softDeletes();
            $table->timestamps();


            // $table->foreign('work_id')->references('id')->on('work')->cascadeOnDelete();
            $table->foreign('question_id')->references('id')->on('questions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}

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
            $table->nullableMorphs('answerable'); // riddle question
            $table->unsignedBigInteger('work_id')->nullable();
            $table->json('possible_answer_ids')->nullable();
            $table->longText('answer')->nullable();
            $table->nullableMorphs('answeredby'); // parent learner facilitator professional 
            $table->enum('answer_type',[
                'TRUE_FALSE' ,'LONG_ANSWER', 'SHORT_ANSWER', 'IMAGE', 'VIDEO', 'AUDIO',
                'OPTION', 'NUMBER', 'FLOW', 'ARRANGE'])->nullable();
            $table->softDeletes();
            $table->timestamps();


            // $table->foreign('work_id')->references('id')->on('work')->cascadeOnDelete();
            // $table->foreign('possible_answer_id')->references('id')->on('possible_answers')->cascadeOnDelete();
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

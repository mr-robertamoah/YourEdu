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
            $table->nullableMorphs('addedby'); // facilitator professional parent learner member
            $table->nullableMorphs('questionable'); // user conversation assessmentsection post discussion message
            $table->text('body');
            $table->enum('state',['PENDING','ANSWERED','COMPLETE','SEEN','SENT','RECEIVED'])->nullable();
            $table->mediumInteger('score_over')->nullable();
            $table->text('hint')->nullable();
            $table->smallInteger('position')->nullable();
            $table->enum('answer_type',[
                'TRUE_FALSE','LONG_ANSWER','SHORT_ANSWER',
                'IMAGE','VIDEO','AUDIO',
                'OPTION','NUMBER','FLOW','ARRANGE']
            )->nullable();
            $table->json('correct_possible_answers')->nullable();
            $table->timestamp('published_at')->nullable();
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

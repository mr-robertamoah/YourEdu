<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessment_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->string('name');
            $table->text('instruction')->nullable();
            $table->smallInteger('position')->nullable();
            $table->mediumInteger('max_questions')->nullable();
            $table->boolean('auto_mark')->default(false);
            $table->boolean('random')->default(false);
            $table->enum('answer_type',[
                'TRUE_FALSE','LONG_ANSWER','SHORT_ANSWER','IMAGE','VIDEO','AUDIO',
                'OPTION','NUMBER','FLOW','ARRANGE'])->nullable();
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('assessment_id')->references('id')->on('assessments')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assessment_sections');
    }
}

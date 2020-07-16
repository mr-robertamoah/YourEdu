<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->unsignedBigInteger('report_section_id');
            $table->unsignedBigInteger('learner_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('total_detail_id')->nullable();
            $table->unsignedBigInteger('grading_detail_id')->nullable();
            $table->float('traditional_mark')->nullable();
            $table->float('traditional_mark_over')->nullable();
            $table->float('total_mark')->nullable();
            $table->float('total_mark_over')->nullable();
            $table->string('comment')->nullable();
            $table->softDeletes();
            $table->timestamps();

            
            $table->foreign('report_id')->references('id')->on('reports')->cascadeOnDelete();
            // $table->foreign('report_section_id')->references('id')->on('report_sections')->cascadeOnDelete();
            $table->foreign('learner_id')->references('id')->on('learners')->cascadeOnDelete();
            // $table->foreign('subject_id')->references('id')->on('subjects')->cascadeOnDelete();
            $table->foreign('grading_detail_id')->references('id')->on('grading_details')->cascadeOnDelete();
            // $table->foreign('total_detail_id')->references('id')->on('total_details')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_details');
    }
}

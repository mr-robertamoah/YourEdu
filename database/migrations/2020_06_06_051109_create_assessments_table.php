<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->morphs('assessmentby'); // school facilitator professional parent
            $table->morphs('assessmentable'); // lesson topic curriculumdetail admission`qz
            $table->unsignedBigInteger('academic_year_section_id')->nullable();
            $table->unsignedBigInteger('report_detail_id')->nullable();
            $table->string('name');
            $table->float('total_mark');
            $table->string('remark')->nullable();
            $table->timestamps();

            
            $table->foreign('academic_year_section_id')->references('id')->on('academic_year_sections')->cascadeOnDelete();
            // $table->foreign('report_detail_id')->references('id')->on('report_details')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assessments');
    }
}

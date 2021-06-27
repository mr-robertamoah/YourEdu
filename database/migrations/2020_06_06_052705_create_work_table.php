<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('addedby'); // learner facilitator professional group
            $table->unsignedBigInteger('assessment_id');
            $table->unsignedBigInteger('report_detail_id')->nullable();
            $table->enum('status', ['PENDING','DONE'])->default('PENDING');
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('assessment_id')->references('id')->on('assessments')->cascadeOnDelete();
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
        Schema::dropIfExists('work');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('learner_id')->nullable();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->nullableMorphs('addedby'); // learner school
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->enum('state',['PENDING','ACCEPTED','DECLINED'])->nullable();
            $table->enum('type',['TRADITIONAL','VIRTUAL'])->nullable();
            $table->softDeletes();
            $table->timestamps();

            
            $table->foreign('school_id')->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('learner_id')->references('id')->on('learners')->cascadeOnDelete();
            $table->foreign('grade_id')->references('id')->on('grades')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admissions');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearnerParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learner_parent', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('learner_id');
            $table->enum('role',['FATHER','MOTHER','GUARDIAN'])->default('GUARDIAN');
            $table->tinyInteger('level')->default(1);
            $table->timestamps();


            $table->foreign('learner_id')->references('id')->on('learners')->cascadeOnDelete();
            $table->foreign('parent_id')->references('id')->on('parent_models')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('learner_parent');
    }
}

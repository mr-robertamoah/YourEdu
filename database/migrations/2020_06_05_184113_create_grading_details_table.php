<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grading_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grading_system_id');
            $table->decimal('max',3,3);
            $table->decimal('min',3,3);
            $table->string('remark')->nullable();
            $table->string('comment')->nullable();
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('grading_system_id')->references('id')->on('grading_systems')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grading_details');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employment_id')->nullable();
            $table->integer('amount')->nullable();
            $table->enum('period',['DAY','WEEK','MONTH','QUARTER','YEAR'])->nullable();
            $table->string('currency')->nullable();
            $table->timestamps();

            
            $table->foreign('employment_id')->references('id')->on('employments')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salaries');
    }
}

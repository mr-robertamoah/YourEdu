<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('markable'); // answer work
            $table->nullableMorphs('markedby'); // professional facilitator parent school
            $table->integer('score')->nullable();
            $table->integer('score_over')->nullable();
            $table->string('remark')->nullable();
            $table->enum('state',['WRONG','PARTIAL','CORRECT'])->nullable();
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
        Schema::dropIfExists('marks');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videoables', function (Blueprint $table) {
            $table->unsignedBigInteger('video_id');
            $table->morphs('videoable'); //question answer profile comment lesson activity
            $table->enum('state',['PUBLIC','PRIVATE'])->nullable();
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('video_id')->references('id')->on('videos')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videoables');
    }
}

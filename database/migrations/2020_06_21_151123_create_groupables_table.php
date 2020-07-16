<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groupables', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->morphs('groupable'); // learner parent professional admin facilitator
            $table->enum('state',['ACTIVE','RESTRAINED','BANNED','REMOVED'])->default('ACTIVE');
            $table->enum('type',['UPLOAD','POST'])->default('UPLOAD');
            $table->timestamp('end_date')->nullable()->default(now());
            $table->timestamps();

            
            $table->foreign('group_id')->references('id')->on('groups')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groupables');
    }
}

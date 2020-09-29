<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id')->nullable();
            $table->enum('followedby_chat_status',['REQUEST','ACCEPT','PENDING','DECLINE','BLOCK'])->nullable();
            $table->nullableMorphs('followedby'); //facilitator learner professional school parent
            $table->unsignedBigInteger('user_id')->nullable();//user id of the one following 
            $table->enum('followable_chat_status',['REQUEST','ACCEPT','PENDING','DECLINE','BLOCK'])->nullable();
            $table->nullableMorphs('followable'); //facilitator learner professional school parent
            $table->unsignedBigInteger('followed_user_id')->nullable();//user id  of ther one being followed
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('follows');
    }
}

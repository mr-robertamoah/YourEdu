<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversationables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->nullableMorphs('accountable'); //learner facilitator parent school professional
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('state',['REQUEST','ACCEPT','PENDING','DECLINE','BLOCK'])->nullable();
            $table->timestamps();


            $table->foreign('conversation_id')->references('id')->on('conversations')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversationables');
    }
}

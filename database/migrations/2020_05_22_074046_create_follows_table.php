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
            $table->nullableMorphs('followedby');
            $table->enum('followable_chat_status',['REQUEST','ACCEPT','PENDING','DECLINE','BLOCK'])->nullable();
            $table->nullableMorphs('followable');
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
        Schema::dropIfExists('follows');
    }
}

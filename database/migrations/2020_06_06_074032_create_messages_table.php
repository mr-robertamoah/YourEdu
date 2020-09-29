<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id')->nullable();
            $table->string('message')->nullable();
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->nullableMorphs('fromable');
            $table->unsignedBigInteger('to_user_id')->nullable();
            $table->nullableMorphs('toable');
            $table->enum('state',['SEEN','SENT','RECEIVED'])->nullable();
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
        Schema::dropIfExists('messages');
    }
}

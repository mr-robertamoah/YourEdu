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
            $table->nullableMorphs('messageable'); //discussions conversation request
            $table->string('message')->nullable();
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->nullableMorphs('fromable');
            $table->unsignedBigInteger('to_user_id')->nullable();
            $table->nullableMorphs('toable');
            $table->enum('state',['SEEN','SENT','RECEIVED', 'ACCEPTED', 'DECLINED', 'PENDING', 'DELETED'])->nullable();
            $table->json('user_deletes')->nullable();
            $table->json('user_seens')->nullable();
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

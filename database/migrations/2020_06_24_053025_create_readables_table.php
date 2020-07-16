<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('readables', function (Blueprint $table) {
            $table->unsignedBigInteger('book_id');
            $table->morphs('readable');
            $table->enum('state',['YET TO START','READING','FINISHED'])->default('YET TO START');
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
        Schema::dropIfExists('readables');
    }
}

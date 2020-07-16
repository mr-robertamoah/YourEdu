<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('read_id');
            $table->morphs('expressionable'); //learner school facilitators professionals parents
            $table->string('epression');
            $table->string('general')->nullable();
            $table->string('context')->nullable();
            $table->string('use')->nullable();
            $table->string('location')->nullable();
            $table->softDeletes();
            $table->timestamps();

            
            $table->foreign('read_id')->references('id')->on('reads')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expessions');
    }
}

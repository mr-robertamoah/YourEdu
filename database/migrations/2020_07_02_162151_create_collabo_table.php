<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollaboTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collabo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collaboration_id');
            $table->nullableMorphs('collaborationable'); // facilitator professional
            $table->enum('state',['PENDING','ACCEPTED','DECLINED'])->default('PENDING');
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
        Schema::dropIfExists('collabo');
    }
}

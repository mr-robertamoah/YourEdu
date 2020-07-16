<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilitatorSchoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facilitator_school', function (Blueprint $table) {
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('facilitator_id');
            $table->enum('relationship',['PART-TIME','FULL-TIME','VIRTUAL','OTHER']);
            $table->text('relationship_description')->nullable();
            $table->timestamps();

            
            $table->foreign('school_id')->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('facilitator_id')->references('id')->on('facilitators')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facilitator_school');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionalSchoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professional_school', function (Blueprint $table) {
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('professional_id');
            $table->enum('relationship',['TRADITIONAL','VIRTUAL','OTHER']);
            $table->string('relationship_description')->nullable();
            $table->timestamps();

            
            $table->foreign('school_id')->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('professional_id')->references('id')->on('professionals')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('professional_school');
    }
}

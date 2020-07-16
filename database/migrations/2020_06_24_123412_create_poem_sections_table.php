<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoemSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poem_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poem_id');
            $table->text('body');
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('poem_id')->references('id')->on('poems')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('poem_sections');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->morphs('priceable'); // book extracurriculum course class classsection request admission permission
            $table->morphs('ownedby'); //professional school facilitator 
            $table->float('amount');
            $table->string('description')->nullable();
            $table->boolean('postponed')->default(false);
            $table->enum('for',['ALL','LEARNERS','PARENTS','FACILITATORS','PROFESSIONALS','SCHOOLS'])->default('ALL');
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
        Schema::dropIfExists('prices');
    }
}

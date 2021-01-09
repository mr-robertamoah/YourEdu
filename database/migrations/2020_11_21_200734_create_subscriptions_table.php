<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->morphs('subsribable'); // book extracurriculum course class classsection request admission permission
            $table->morphs('ownedby'); //professional school facilitator 
            $table->string('name')->nullable();
            $table->float('amount');
            $table->string('description')->nullable();
            $table->enum('period',['MONTH','QUARTER','YEAR'])->default('YEAR');
            $table->enum('for',['ALL','LEARNERS','PARENTS','FACILITATORS','PROFESSIONALS','SCHOOLS'])->default('ALL');
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
        Schema::dropIfExists('subscriptions');
    }
}

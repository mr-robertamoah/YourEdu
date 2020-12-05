<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->nullableMorphs('addedby'); //facilitator admin
            $table->nullableMorphs('ownedby'); //facilitator school
            $table->smallInteger('max_learners')->nullable();
            $table->string('structure')->nullable();
            $table->enum('state',['PENDING','DECLINED','ACCEPTED'])->nullable();
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
        Schema::dropIfExists('class_models');
    }
}

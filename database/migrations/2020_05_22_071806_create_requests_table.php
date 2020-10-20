<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('requestable'); // follow permission group collaboration extracurriculum subject grade class
            $table->nullableMorphs('requestfrom'); // professional school group facilitator
            $table->nullableMorphs('requestto'); // parent school professional facilitator learner
            $table->enum('state',['PENDING','ACCEPTED','DECLINED'])->default('PENDING');
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
        Schema::dropIfExists('requests');
    }
}

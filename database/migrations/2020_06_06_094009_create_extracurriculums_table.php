<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtracurriculumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extracurriculums', function (Blueprint $table) {
            $table->id();
            $table->morphs('addedby'); // facilitator school professional
            $table->morphs('ownedby'); // facilitator school professional
            $table->string('name');
            $table->string('description')->nullable();
            $table->enum('state',['PENDING','DECLINED','ACCEPTED','DELETED'])->nullable();
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
        Schema::dropIfExists('extracurriculums');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('addedby'); // facilitator school admin professional
            $table->nullableMorphs('ownedby'); // facilitator school professional
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('rationale')->nullable();
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
        Schema::dropIfExists('programs');
    }
}

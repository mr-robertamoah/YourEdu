<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('ownedby'); // facilitator learner professional parent school
            $table->nullableMorphs('createdby'); // facilitator learner professional admin parent
            $table->string('description')->nullable();
            $table->enum('state',['PUBLIC','PRIVATE'])->default('PUBLIC');
            $table->enum('membership',['FACILITATORS','PARENTS','PROFESSIONALS','ADMINS','LEARNERS'])->nullable();
            $table->smallInteger('max_members')->default(50);
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
        Schema::dropIfExists('groups');
    }
}

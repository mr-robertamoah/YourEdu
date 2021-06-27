<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('flag_id')->nullable();
            $table->mediumText('reason')->nullable();
            $table->nullableMorphs('flaggedby'); // parent learner facilitator professional
            $table->nullableMorphs('flaggable'); // comment post course answer lesson facilitator professional school learner extracurriculum
            $table->enum('status', ['PENDING', 'APPROVED', 'CANCELLED'])->default('PENDING');
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
        Schema::dropIfExists('flags');
    }
}

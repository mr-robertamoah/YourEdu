<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->nullableMorphs('addedby');
            $table->timestamp('start_date')->default(now());
            $table->timestamp('end_date')->default(now()->add('year',1));
            $table->string('description')->nullable();
            $table->enum('state',['PENDING','ACCEPTED','DECLINED'])->nullable();
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('school_id')->references('id')->on('schools')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academic_years');
    }
}

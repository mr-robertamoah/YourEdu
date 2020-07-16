<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->morphs('issuedfor'); // flag complaint
            $table->enum('state',['PENDING','RESOLVED','SERVED'])->default('PENDING');
            $table->enum('type',['OVERALL','POST','UPLOAD','COMMENT']);
            $table->morphs('bannable'); // learner parent school facilitator professional
            $table->timestamp('due_date')->nullable();
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('admin_id')->references('id')->on('admins')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bans');
    }
}

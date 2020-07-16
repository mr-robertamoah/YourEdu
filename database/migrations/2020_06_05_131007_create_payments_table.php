<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->morphs('paidby'); //member facilitator professional school parent groupadmin
            $table->morphs('paidto'); // facilitator professional school youredu
            $table->morphs('paidfor'); //price fee
            $table->nullableMorphs('for'); // learner group faciliator school parent professional
            $table->float('actual_amount')->nullable();
            $table->float('amount_paid');
            $table->enum('state',['PENDING','COMPLETED','CANCELLED'])->nullable();
            $table->timestamp('postponement_date')->nullable();
            $table->timestamps();


            $table->foreign('account_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}

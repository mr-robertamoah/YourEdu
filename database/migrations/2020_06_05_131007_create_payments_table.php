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
            $table->nullableMorphs('paidby'); //member facilitator professional school parent groupadmin
            $table->nullableMorphs('paidto'); // facilitator professional school youredu
            $table->nullableMorphs('paidfor'); //price fee subscription commission
            $table->nullableMorphs('what'); //course program class
            $table->nullableMorphs('for'); // learner group faciliator school parent professional
            $table->float('discount_id')->nullable();
            $table->float('amount');
            $table->enum('type',['CASH','CARD','MOMO','BANK']);
            $table->enum('state',['PENDING','COMPLETED','CANCELLED'])->nullable();
            $table->timestamp('postponement_date')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();


            // $table->foreign('account_id')->references('id')->on('users')->cascadeOnDelete();
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

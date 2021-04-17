<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salariables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_id')->nullable()->constrained();
            $table->foreignId('employment_id')->nullable()->constrained();
            $table->nullableMorphs('salariable');
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
        Schema::dropIfExists('salariables');
    }
}

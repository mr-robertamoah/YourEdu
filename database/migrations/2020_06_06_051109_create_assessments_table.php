<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('addedby'); // learner school facilitator professional parent
            $table->string('name');
            $table->text('description')->nullable();
            $table->float('total_mark')->default(100);
            $table->integer('duration')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->enum('type',['PUBLIC','PRIVATE']);
            $table->boolean('social')->default(false);
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
        Schema::dropIfExists('assessments');
    }
}

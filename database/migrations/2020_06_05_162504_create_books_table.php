<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('addedby'); // learner facilitator professional parent
            $table->nullableMorphs('authoredby'); // learner facilitator professional school parent
            $table->nullableMorphs('bookable'); // post lesson
            $table->string('title'); 
            $table->string('author_names')->nullable();
            $table->text('about')->nullable();
            $table->timestamp('published_at')->nullable();
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
        Schema::dropIfExists('books');
    }
}

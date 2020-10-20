<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { //may be used for attachments other than posts
        Schema::create('post_attachments', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('attachedby'); // facilitator professional school
            $table->nullableMorphs('attachable'); // post
            $table->nullableMorphs('attachedwith'); // subject curriculumdetail  grade
            $table->string('note')->nullable();
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
        Schema::dropIfExists('post_attachments');
    }
}

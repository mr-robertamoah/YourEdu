<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_medias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id');
            $table->string('username')->nullable();
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->enum('type',['FACEBOOK','TWITTER','INSTAGRAM','YOUTUBE','OTHER'])->nullable();
            $table->boolean('show')->default(true);
            $table->softDeletes();
            $table->timestamps();

            
            // $table->foreign('profile_id')->references('id')->on('profiles')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_medias');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('poster_image', 255);
            $table->string('preview_image', 255);
            $table->string('background_image', 255);
            $table->char('background_color', 9);
            $table->dateTime('released');
            $table->string('description', 1000);
            $table->smallInteger('run_time')->unsigned();
            $table->string('video_link', 255);
            $table->string('preview_video_link', 255);
            $table->string('imdb_id')->unique();
            $table->set('status', ['pending', 'moderate', 'ready'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};

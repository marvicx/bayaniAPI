<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('information_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->text('content');
            $table->string('author');
            $table->string('category');
            $table->json('tags')->nullable();
            $table->dateTime('published_date')->nullable();
            $table->boolean('is_published')->default(false);
            $table->integer('views')->default(0);
            $table->string('attachments')->nullable();
            $table->string('userID')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('information_posts');
    }
};
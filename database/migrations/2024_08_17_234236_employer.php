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
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->string('companyName')->nullable();
            $table->string('companyType')->nullable();
            $table->string('same_as')->nullable(); // URL of the employer's website
            $table->string('logo')->nullable(); // URL of the employer's logo
            $table->string('industry')->nullable(); // Industry the employer operates in
            $table->text('description')->nullable(); // Description of the employer
            $table->longText('mission')->nullable();
            $table->longText('vision')->nullable();
            $table->integer('addressID')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employers');
    }
};
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
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('FirstName');
            $table->string('LastName');
            $table->string('MiddleName');
            $table->string('suffix');
            $table->date('birthdate');
            $table->string('gender');
            $table->string('civilStatus');
            $table->string('religion');
            $table->string('educationalAttainment');
            $table->string('course');
            $table->integer('addressID');
            $table->string('employmentDetailsID');
            $table->string('tags');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};

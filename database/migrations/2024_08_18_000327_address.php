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
        Schema::create('address', function (Blueprint $table) {
            $table->id();
            $table->string('provinceID')->nullable();
            $table->string('cityID')->nullable();
            $table->string('barangayID')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('street')->nullable();
            $table->string('mobileNo')->nullable();
            $table->string('email')->nullable();
            $table->string('telephoneNo')->nullable();
            $table->string('fax')->nullable();
            $table->string('ofwForeignAddress')->nullable();
            $table->string('ofwCountry')->nullable();
            $table->string('ofwContactNo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address');
    }
};
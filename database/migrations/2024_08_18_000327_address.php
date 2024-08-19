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
            $table->string('userID');
            $table->string('provinceID');
            $table->string('cityID');
            $table->string('barangayID');
            $table->string('zipcode');
            $table->string('street');
            $table->string('mobileNo');
            $table->string('email');
            $table->string('telephoneNo');
            $table->string('fax');
            $table->string('ofwForeignAddress');
            $table->string('ofwCountry');
            $table->string('ofwContactNo');
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

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
        Schema::create('barangays', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();// Primary key
            $table->string('name');
            $table->string('oldName')->nullable();
            $table->string('subMunicipalityCode')->nullable();
            $table->string('cityCode')->nullable(); 
            $table->string('municipalityCode')->nullable();
            $table->string('districtCode')->nullable();
            $table->string('provinceCode')->nullable();
            $table->string('regionCode')->nullable();
            $table->string('islandGroupCode')->nullable();
            $table->string('psgc10DigitCode')->nullable();
            $table->timestamps(); // Optional: if you want to include created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangays');
    }
};

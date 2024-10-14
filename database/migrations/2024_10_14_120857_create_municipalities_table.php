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
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('oldName')->nullable();
            $table->boolean('isCapital')->default(false);
            $table->boolean('isCity')->default(false);
            $table->boolean('isMunicipality')->default(false);
            $table->string('districtCode')->nullable();
            $table->string('provinceCode')->nullable();
            $table->string('regionCode')->nullable();
            $table->string('islandGroupCode')->nullable();
            $table->string('psgc10DigitCode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipalities');
    }
};

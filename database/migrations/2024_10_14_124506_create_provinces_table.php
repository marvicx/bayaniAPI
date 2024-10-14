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
        Schema::create('provinces', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('code')->unique(); // Province code
            $table->string('name'); // Province name
            $table->string('regionCode'); // Region code
            $table->string('islandGroupCode'); // Island group code
            $table->string('psgc10DigitCode')->unique(); // PSGC 10-digit code
            $table->timestamps(); // Created at and Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};

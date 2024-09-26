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
        Schema::create('verificationotp', function (Blueprint $table) {
            $table->id();
            $table->string('identifier'); // E.g., email or phone number
            $table->string('token'); // The verification code
            $table->timestamp('validity'); // When the token expires
            $table->boolean('valid'); // To indicate if the token is still valid
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

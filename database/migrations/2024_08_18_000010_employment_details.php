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
        Schema::create('employment_Details', function (Blueprint $table) {
            $table->id();
            $table->string('employerName');
            $table->string('personID')->nullable();
            $table->string('vessel')->nullable();
            $table->string('occupation')->nullable();
            $table->string('monthlySalary')->nullable();
            $table->string('agencyName')->nullable();
            $table->string('contractDuration')->nullable();
            $table->string('ofwType')->nullable();
            $table->string('jobSite')->nullable();
            $table->string('passport_attachment')->nullable();
            $table->string('coe_attachment')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employmentDetails');
    }
};
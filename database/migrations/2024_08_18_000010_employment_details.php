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
            $table->string('employerAddressID');
            $table->string('vessel');
            $table->string('occupation');
            $table->string('monthlySalary');
            $table->string('agencyName');
            $table->string('contractDuration');
            $table->string('ofwType');
            $table->string('jobSite');
            $table->string('passport_attachment');
            $table->string('coe_attachment');
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

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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('date_posted');
            $table->date('valid_through')->nullable();
            $table->string('employment_type');
            $table->string('hiring_organization_name');
            $table->string('hiring_organization_same_as')->nullable();
            $table->string('hiring_organization_logo')->nullable();
            $table->string('job_location_street_address');
            $table->string('job_location_address_locality');
            $table->string('job_location_address_region');
            $table->string('job_location_postal_code');
            $table->string('job_location_address_country');
            $table->string('base_salary_value')->nullable();
            $table->string('base_salary_currency', 3);
            $table->string('base_salary_unit_text')->nullable();
            $table->text('job_benefits')->nullable();
            $table->text('responsibilities')->nullable();
            $table->text('qualifications')->nullable();
            $table->text('skills')->nullable();
            $table->string('industry')->nullable();
            $table->string('applicant_location_requirements')->nullable();
            $table->string('job_location_type')->nullable();
            $table->string('work_hours')->nullable();
            $table->string('tags')->nullable();
            $table->integer('status')->default(1);
            $table->integer('postedby')->nullable();
            $table->string('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};

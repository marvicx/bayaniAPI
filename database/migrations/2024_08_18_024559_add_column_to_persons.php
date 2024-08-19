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
        Schema::table('persons', function (Blueprint $table) {
            Schema::table('persons', function (Blueprint $table) {
                $table->string('FirstName')->nullable()->change();
                $table->string('LastName')->nullable()->change();
                $table->string('MiddleName')->nullable()->change();
                $table->string('suffix')->nullable()->change();
                $table->date('birthdate')->nullable()->change();
                $table->string('gender')->nullable()->change();
                $table->string('civilStatus')->nullable()->change();
                $table->string('religion')->nullable()->change();
                $table->string('educationalAttainment')->nullable()->change();
                $table->string('course')->nullable()->change();
                $table->integer('addressID')->nullable()->change();
                $table->string('employmentDetailsID')->nullable()->change();
                $table->string('tags')->nullable()->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            Schema::table('persons', function (Blueprint $table) {
                $table->string('FirstName')->nullable(false)->change();
                $table->string('LastName')->nullable(false)->change();
                $table->string('MiddleName')->nullable(false)->change();
                $table->string('suffix')->nullable(false)->change();
                $table->date('birthdate')->nullable(false)->change();
                $table->string('gender')->nullable(false)->change();
                $table->string('civilStatus')->nullable(false)->change();
                $table->string('religion')->nullable(false)->change();
                $table->string('educationalAttainment')->nullable(false)->change();
                $table->string('course')->nullable(false)->change();
                $table->integer('addressID')->nullable(false)->change();
                $table->string('employmentDetailsID')->nullable(false)->change();
                $table->string('tags')->nullable(false)->change();
            });
        });
    }
};

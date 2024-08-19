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
        Schema::table('users', function (Blueprint $table) {
            // Check if the column exists before modifying it
            if (Schema::hasColumn('users', 'personID')) {
                $table->integer('personID')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // If you need to revert the column to non-nullable, uncomment this
            // and modify the column type as needed.
            if (Schema::hasColumn('users', 'personID')) {
                $table->integer('personID')->nullable(false)->change();
            }
        });
    }
};

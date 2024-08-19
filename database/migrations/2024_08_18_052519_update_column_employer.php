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
        Schema::table('employers', function (Blueprint $table) {
            $table->string('companyName')->nullable()->change();
            $table->string('companyType')->nullable()->change();
            $table->string('same_as')->nullable()->nullable()->change(); // URL of the employer's website
            $table->string('logo')->nullable()->nullable()->change(); // URL of the employer's logo
            $table->string('industry')->nullable()->nullable()->change(); // Industry the employer operates in
            $table->text('description')->nullable()->nullable()->change(); // Description of the employer
            $table->longText('mission')->nullable()->change();
            $table->longText('vission')->nullable()->change();
            $table->integer('addressID')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employers', function (Blueprint $table) {
            $table->string('companyName')->nullable(false)->change();
            $table->string('companyType')->nullable(false)->change();
            $table->string('same_as')->nullable()->nullable(false)->change(); // URL of the employer's website
            $table->string('logo')->nullable()->nullable(false)->change(); // URL of the employer's logo
            $table->string('industry')->nullable()->nullable(false)->change(); // Industry the employer operates in
            $table->text('description')->nullable()->nullable(false)->change(); // Description of the employer
            $table->longText('mission')->nullable(false)->change();
            $table->longText('vission')->nullable(false)->change();
            $table->integer('addressID')->nullable(false)->change();
        });
    }
};

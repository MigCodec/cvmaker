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
        Schema::create('cvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('target_role')->nullable();
            $table->text('summary')->nullable();
            $table->string('template')->default('default');
            $table->timestamps();
        });

        Schema::create('cv_experience', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cvs')->cascadeOnDelete();
            $table->foreignId('experience_id')->constrained('experiences')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['cv_id', 'experience_id']);
        });

        Schema::create('cv_education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cvs')->cascadeOnDelete();
            $table->foreignId('education_id')->constrained('education')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['cv_id', 'education_id']);
        });

        Schema::create('certificate_cv', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cvs')->cascadeOnDelete();
            $table->foreignId('certificate_id')->constrained('certificates')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['cv_id', 'certificate_id']);
        });

        Schema::create('cv_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cvs')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['cv_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_skill');
        Schema::dropIfExists('certificate_cv');
        Schema::dropIfExists('cv_education');
        Schema::dropIfExists('cv_experience');
        Schema::dropIfExists('cvs');
    }
};

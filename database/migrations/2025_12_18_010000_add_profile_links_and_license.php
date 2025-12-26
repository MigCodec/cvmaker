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
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('linkedin')->nullable()->after('website');
            $table->string('github')->nullable()->after('linkedin');
            $table->boolean('driver_license')->default(false)->after('github');
            $table->string('driver_license_type')->nullable()->after('driver_license');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'linkedin',
                'github',
                'driver_license',
                'driver_license_type',
            ]);
        });
    }
};

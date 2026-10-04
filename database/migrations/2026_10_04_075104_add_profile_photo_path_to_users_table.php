<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'profile_photo_path')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo_path')->nullable()->after('password');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'profile_photo_path')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_photo_path');
        });
    }
};
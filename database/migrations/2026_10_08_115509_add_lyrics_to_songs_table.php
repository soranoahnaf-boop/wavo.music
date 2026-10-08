<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    | Lyrics are stored as plain text. Two formats are supported:
    |
    |  - LRC (synced):   [01:12.50] line of the song
    |  - Plain (unsynced): one line per row, no timestamps
    */
    public function up(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->longText('lyrics')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->dropColumn('lyrics');
        });
    }
};

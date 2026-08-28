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
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('nip')->nullable()->after('name');
            $table->string('employment_status')->nullable()->default('GTT')->after('subject'); // PNS, PPPK, GTY, GTT
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->boolean('is_pinned')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['nip', 'employment_status']);
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('is_pinned');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('extracurriculars', function (Blueprint $table) {
            $table->json('images')->nullable()->after('image');
        });

        DB::table('extracurriculars')
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->orderBy('id')
            ->eachById(function (object $extracurricular): void {
                DB::table('extracurriculars')
                    ->where('id', $extracurricular->id)
                    ->update(['images' => json_encode([$extracurricular->image])]);
            });
    }

    public function down(): void
    {
        Schema::table('extracurriculars', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};

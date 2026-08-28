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
        Schema::create('ppdb_applicants', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('academic_year')->index();
            $table->string('student_name');
            $table->string('nik', 32);
            $table->string('nisn', 32)->nullable();
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('gender', 16);
            $table->text('address');
            $table->string('previous_school')->nullable();
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('guardian_name')->nullable();
            $table->string('parent_job')->nullable();
            $table->string('parent_phone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_applicants');
    }
};

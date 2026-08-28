<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_item_id')->constrained()->cascadeOnDelete();
            $table->string('section')->index();
            $table->string('alt_text')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['section', 'gallery_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_images');
    }
};

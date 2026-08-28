<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\OfficialVisionMissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OfficialVisionMissionSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_official_vision_and_mission_are_imported(): void
    {
        Storage::fake('public');

        $this->seed(OfficialVisionMissionSeeder::class);

        $page = Page::query()->where('slug', 'visi-misi')->firstOrFail();

        $this->assertStringContainsString('visi-misi-mi-hubbul-wathan.jpg', $page->body);
        $this->assertStringContainsString('Terwujudnya insan yang bertakwa', $page->body);
        $this->assertStringContainsString('Program Madrasah Bersih (PMB)', $page->body);
        $this->assertStringContainsString('sehat jasmani dan rohani', $page->body);

        Storage::disk('public')->assertExists('pages/visi-misi-mi-hubbul-wathan.jpg');
    }
}

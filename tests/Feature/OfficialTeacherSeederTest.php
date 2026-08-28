<?php

namespace Tests\Feature;

use App\Models\Teacher;
use Database\Seeders\OfficialTeacherSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfficialTeacherSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_official_teacher_structure_is_imported(): void
    {
        Teacher::create([
            'name' => 'Guru Dummy',
            'slug' => 'guru-dummy',
            'position' => 'Guru',
            'status' => 'published',
        ]);

        $this->seed(OfficialTeacherSeeder::class);

        $this->assertSame(10, Teacher::query()->where('status', 'published')->count());

        $this->assertDatabaseHas('teachers', [
            'name' => 'Hasnah, S.Pd.I',
            'position' => 'Kepala Madrasah',
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('teachers', [
            'name' => 'Mukhadar, S.Pd',
            'position' => 'Tata Usaha',
            'subject' => 'Bendahara / Wali Kelas VI / Pembina Pramuka',
        ]);

        $this->assertDatabaseHas('teachers', [
            'name' => 'Magfirah, S.E',
            'subject' => 'Wali Kelas II / Ketua UKS',
        ]);

        $this->assertDatabaseHas('teachers', [
            'slug' => 'guru-dummy',
            'status' => 'draft',
        ]);
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\AcademicEvent;
use App\Models\Announcement;
use App\Models\DownloadDocument;
use App\Models\GalleryItem;
use App\Models\Post;
use App\Models\PpdbApplicant;
use App\Models\PpdbDocument;
use App\Models\Teacher;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class PortalAnalyticsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan Portal';

    protected ?string $description = 'Pantauan cepat konten sekolah, PPDB, dan data publikasi.';

    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected int | array | null $columns = [
        'default' => 1,
        'md' => 2,
        'xl' => 4,
    ];

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    protected function getStats(): array
    {
        $todayApplicants = PpdbApplicant::query()
            ->whereDate('created_at', Carbon::today())
            ->count();

        $lastSevenDaysApplicants = PpdbApplicant::query()
            ->where('created_at', '>=', Carbon::today()->subDays(6))
            ->count();

        $publishedPosts = Post::query()->where('status', 'published')->count();
        $draftPosts = Post::query()->where('status', 'draft')->count();

        $publishedAnnouncements = Announcement::query()->where('status', 'published')->count();
        $draftAnnouncements = Announcement::query()->where('status', 'draft')->count();

        $upcomingEvents = AcademicEvent::query()
            ->where('starts_at', '>=', now())
            ->where('status', 'published')
            ->count();

        return [
            Stat::make('Total pendaftar PPDB', number_format(PpdbApplicant::query()->count(), 0, ',', '.'))
                ->description("{$todayApplicants} pendaftar hari ini")
                ->descriptionIcon(Heroicon::ArrowTrendingUp)
                ->color('success')
                ->icon(Heroicon::ClipboardDocumentList)
                ->chart($this->dailyApplicantChart()),

            Stat::make('Pendaftar 7 hari', number_format($lastSevenDaysApplicants, 0, ',', '.'))
                ->description('Akumulasi pendaftaran pekan ini')
                ->descriptionIcon(Heroicon::CalendarDays)
                ->color('primary')
                ->icon(Heroicon::ChartBar),

            Stat::make('Berita sekolah', number_format($publishedPosts, 0, ',', '.'))
                ->description("{$draftPosts} masih draf")
                ->descriptionIcon(Heroicon::DocumentText)
                ->color($draftPosts > 0 ? 'warning' : 'success')
                ->icon(Heroicon::Newspaper),

            Stat::make('Pengumuman', number_format($publishedAnnouncements, 0, ',', '.'))
                ->description("{$draftAnnouncements} masih draf")
                ->descriptionIcon(Heroicon::Bell)
                ->color($draftAnnouncements > 0 ? 'warning' : 'success')
                ->icon(Heroicon::Megaphone),

            Stat::make('Dokumen PPDB', number_format(PpdbDocument::query()->count(), 0, ',', '.'))
                ->description('Berkas pendaftar yang sudah masuk')
                ->descriptionIcon(Heroicon::FolderOpen)
                ->color('primary')
                ->icon(Heroicon::DocumentCheck),

            Stat::make('Guru aktif', number_format(Teacher::query()->where('status', 'published')->count(), 0, ',', '.'))
                ->description('Profil guru tampil di portal')
                ->descriptionIcon(Heroicon::AcademicCap)
                ->color('success')
                ->icon(Heroicon::Users),

            Stat::make('Foto galeri', number_format(GalleryItem::query()->where('status', 'published')->count(), 0, ',', '.'))
                ->description('Foto yang tampil untuk publik')
                ->descriptionIcon(Heroicon::Camera)
                ->color('primary')
                ->icon(Heroicon::Photo),

            Stat::make('Agenda mendatang', number_format($upcomingEvents, 0, ',', '.'))
                ->description(number_format(DownloadDocument::query()->where('status', 'published')->count(), 0, ',', '.') . ' dokumen unduhan aktif')
                ->descriptionIcon(Heroicon::CalendarDateRange)
                ->color('warning')
                ->icon(Heroicon::Calendar),
        ];
    }

    /**
     * @return array<int>
     */
    private function dailyApplicantChart(): array
    {
        $startDate = Carbon::today()->subDays(6);

        $applicants = PpdbApplicant::query()
            ->where('created_at', '>=', $startDate)
            ->get(['created_at'])
            ->groupBy(fn (PpdbApplicant $applicant): string => $applicant->created_at->toDateString());

        return collect(range(0, 6))
            ->map(fn (int $day): int => $applicants->get($startDate->copy()->addDays($day)->toDateString(), collect())->count())
            ->all();
    }
}

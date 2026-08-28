<?php

namespace App\Filament\Widgets;

use App\Models\Announcement;
use App\Models\DownloadDocument;
use App\Models\GalleryItem;
use App\Models\Page;
use App\Models\Post;
use Filament\Widgets\ChartWidget;

class ContentPublicationStatus extends ChartWidget
{
    protected ?string $heading = 'Status Publikasi Konten';

    protected ?string $description = 'Perbandingan konten terbit dan draf di portal publik.';

    protected static bool $isLazy = false;

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'xl' => 1,
    ];

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    protected function getData(): array
    {
        $published = $this->countByStatus('published');
        $draft = $this->countByStatus('draft');

        return [
            'datasets' => [
                [
                    'data' => [$published, $draft],
                    'backgroundColor' => ['#059669', '#f59e0b'],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => ['Terbit', 'Draf'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    private function countByStatus(string $status): int
    {
        return Post::query()->where('status', $status)->count()
            + Announcement::query()->where('status', $status)->count()
            + Page::query()->where('status', $status)->count()
            + DownloadDocument::query()->where('status', $status)->count()
            + GalleryItem::query()->where('status', $status)->count();
    }
}

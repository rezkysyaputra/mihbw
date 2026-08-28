<?php

namespace App\Filament\Widgets;

use App\Models\PpdbApplicant;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PpdbApplicantTrendChart extends ChartWidget
{
    protected ?string $heading = 'Tren Pendaftar PPDB';

    protected ?string $description = 'Jumlah pendaftar per bulan dalam 6 bulan terakhir.';

    protected static bool $isLazy = false;

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'xl' => 2,
    ];

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    protected function getData(): array
    {
        $startMonth = Carbon::now()->startOfMonth()->subMonths(5);

        $applicants = PpdbApplicant::query()
            ->where('created_at', '>=', $startMonth)
            ->get(['created_at'])
            ->groupBy(fn (PpdbApplicant $applicant): string => $applicant->created_at->format('Y-m'));

        $months = collect(range(0, 5))
            ->map(fn (int $month): Carbon => $startMonth->copy()->addMonths($month));

        return [
            'datasets' => [
                [
                    'label' => 'Pendaftar',
                    'data' => $months
                        ->map(fn (Carbon $month): int => $applicants->get($month->format('Y-m'), collect())->count())
                        ->all(),
                    'borderColor' => '#059669',
                    'backgroundColor' => 'rgba(5, 150, 105, 0.14)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $months
                ->map(fn (Carbon $month): string => $month->translatedFormat('M Y'))
                ->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

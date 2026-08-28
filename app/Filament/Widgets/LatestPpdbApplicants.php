<?php

namespace App\Filament\Widgets;

use App\Models\PpdbApplicant;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestPpdbApplicants extends TableWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'xl' => 2,
    ];

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Pendaftar PPDB Terbaru')
            ->description('Data terakhir yang masuk melalui formulir PPDB publik.')
            ->query(PpdbApplicant::query()->latest())
            ->columns([
                TextColumn::make('registration_number')
                    ->label('Nomor')
                    ->searchable()
                    ->weight('semibold'),
                TextColumn::make('student_name')
                    ->label('Nama calon siswa')
                    ->searchable()
                    ->description(fn (PpdbApplicant $record): string => $record->parent_phone),
                TextColumn::make('academic_year')
                    ->label('Tahun ajaran')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Masuk')
                    ->since()
                    ->sortable(),
            ])
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25]);
    }
}

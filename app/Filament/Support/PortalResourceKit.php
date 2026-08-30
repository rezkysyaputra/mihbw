<?php

namespace App\Filament\Support;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortalResourceKit
{
    public static function disk(): string
    {
        return config('filesystems.default') === 's3' ? 's3' : 'public';
    }

    public static function statusOptions(): array
    {
        return [
            'draft' => 'Draf',
            'published' => 'Dipublikasikan',
            'archived' => 'Diarsipkan',
        ];
    }

    public static function contentForm(Schema $schema, bool $image = false, string $imageDirectory = 'posts', bool $hasPinned = false): Schema
    {
        $components = [
            TextInput::make('title')
                ->label('Judul')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                    if ($operation === 'create' && filled($state)) {
                        $set('slug', Str::slug($state));
                    }
                }),
            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(255),
            Textarea::make('excerpt')
                ->label('Ringkasan')
                ->helperText('Kosongkan untuk otomatis mengambil 150 karakter pertama dari isi.')
                ->rows(3)
                ->columnSpanFull(),
            RichEditor::make('body')
                ->label('Isi Konten')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function (?string $state, Get $get, Set $set) {
                    if (blank($get('excerpt')) && filled($state)) {
                        $set('excerpt', Str::limit(strip_tags($state), 150));
                    }
                })
                ->columnSpanFull(),
        ];

        if ($image) {
            $components[] = FileUpload::make('cover_image')
                ->label('Gambar Sampul')
                ->image()
                ->imageEditor()
                ->disk(self::disk())
                ->directory($imageDirectory);
        }

        $components[] = Select::make('status')
            ->label('Status')
            ->options(self::statusOptions())
            ->default('published')
            ->required();

        if ($hasPinned) {
            $components[] = Toggle::make('is_pinned')
                ->label('Sematkan di Atas (Pinned)')
                ->helperText('Pengumuman penting akan selalu tampil di baris teratas.')
                ->default(false);
        }

        $components[] = DateTimePicker::make('published_at')
            ->label('Tanggal Publikasi')
            ->default(now());

        return $schema->components($components)->columns(2);
    }

    public static function simpleContentTable(Table $table, string $type = 'post'): Table
    {
        $columns = [
            TextColumn::make('title')->label('Judul')->searchable()->sortable()->wrap(),
        ];

        if ($type === 'announcement') {
            $columns[] = IconColumn::make('is_pinned')
                ->label('Disematkan')
                ->boolean()
                ->sortable();
        }

        $columns[] = TextColumn::make('status')
            ->label('Status')
            ->formatStateUsing(fn (string $state): string => self::statusOptions()[$state] ?? $state)
            ->badge()
            ->color(fn (string $state): string => match ($state) {
                'published' => 'success',
                'draft' => 'warning',
                'archived' => 'danger',
                default => 'gray',
            })
            ->sortable();

        $columns[] = TextColumn::make('published_at')->label('Tanggal Publikasi')->dateTime('d M Y H:i')->sortable();
        $columns[] = TextColumn::make('updated_at')->label('Diperbarui')->dateTime('d M Y H:i')->sortable()->toggleable(isToggledHiddenByDefault: true);

        return $table
            ->columns($columns)
            ->filters([
                SelectFilter::make('status')->label('Status')->options(self::statusOptions()),
            ])
            ->recordActions([
                Action::make('view_public')
                    ->label('Lihat')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn ($record) => $type === 'post' ? route('posts.show', $record->slug) : route('announcements.show', $record->slug))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function pageForm(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('Judul')->required()->maxLength(255),
            TextInput::make('slug')->label('Slug')->required()->maxLength(255),
            Textarea::make('excerpt')->label('Ringkasan')->rows(3)->columnSpanFull(),
            RichEditor::make('body')->label('Isi')->required()->columnSpanFull(),
            Select::make('status')->label('Status')->options(self::statusOptions())->default('published')->required(),
            DateTimePicker::make('published_at')->label('Tanggal publikasi')->default(now()),
        ])->columns(2);
    }

    public static function teacherForm(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nama Lengkap & Gelar')->required()->maxLength(255),
            TextInput::make('nip')->label('NIP / NUPTK / PegID')->helperText('Opsional, kosongkan jika belum memiliki')->maxLength(255),
            TextInput::make('slug')->label('Slug')->required()->maxLength(255),
            TextInput::make('position')->label('Jabatan Utama')->placeholder('Contoh: Guru Kelas 1, Kepala Madrasah')->required()->maxLength(255),
            TextInput::make('subject')->label('Tugas / Mata Pelajaran')->placeholder('Contoh: Tematik, Al-Quran Hadits')->maxLength(255),
            Select::make('employment_status')
                ->label('Status Kepegawaian')
                ->options([
                    'PNS' => 'PNS / ASN',
                    'PPPK' => 'PPPK',
                    'GTY' => 'Guru Tetap Yayasan (GTY)',
                    'GTT' => 'Guru Tidak Tetap (GTT)',
                    'PTT' => 'Pegawai Tidak Tetap',
                ])
                ->default('GTT'),
            FileUpload::make('photo')->label('Foto Profil')->image()->imageEditor()->disk(self::disk())->directory('teachers'),
            Select::make('status')->label('Status')->options(self::statusOptions())->default('published')->required(),
            TextInput::make('sort_order')->label('Urutan Tampilan')->helperText('Makin kecil angkanya, makin di atas posisinya')->numeric()->default(0),
        ])->columns(2);
    }

    public static function extracurricularForm(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nama Ekstrakurikuler')->required()->maxLength(255),
            TextInput::make('slug')->label('Slug')->required()->maxLength(255),
            TextInput::make('coach')->label('Pembina / Pelatih')->maxLength(255),
            TextInput::make('schedule')->label('Jadwal Kegiatan')->placeholder('Contoh: Setiap Jumat, 15.30 WITA')->maxLength(255),
            FileUpload::make('images')
                ->label('Foto Dokumentasi Kegiatan')
                ->image()
                ->multiple()
                ->reorderable()
                ->appendFiles()
                ->maxFiles(12)
                ->disk(self::disk())
                ->directory('extracurriculars')
                ->columnSpanFull(),
            Textarea::make('description')->label('Deskripsi Kegiatan')->rows(4)->columnSpanFull(),
            Select::make('status')->label('Status')->options(self::statusOptions())->default('published')->required(),
        ])->columns(2);
    }

    public static function eventForm(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('Nama Agenda / Kegiatan')->required()->maxLength(255),
            TextInput::make('location')->label('Tempat / Lokasi')->default('MI Hubbul Wathan')->maxLength(255),
            DateTimePicker::make('starts_at')->label('Waktu Mulai')->required(),
            DateTimePicker::make('ends_at')->label('Waktu Selesai'),
            Textarea::make('description')->label('Keterangan Agenda')->rows(4)->columnSpanFull(),
            Select::make('status')->label('Status')->options(self::statusOptions())->default('published')->required(),
        ])->columns(2);
    }

    public static function documentForm(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('Nama Dokumen')->required()->maxLength(255),
            TextInput::make('slug')->label('Slug')->required()->maxLength(255),
            FileUpload::make('file_path')
                ->label('Berkas File')
                ->disk(self::disk())
                ->directory('documents')
                ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'image/jpeg', 'image/png'])
                ->required(),
            Textarea::make('description')->label('Deskripsi Dokumen')->rows(3)->columnSpanFull(),
            Select::make('status')->label('Status')->options(self::statusOptions())->default('published')->required(),
        ])->columns(2);
    }

    public static function albumForm(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('Judul Album')->required()->maxLength(255),
            TextInput::make('slug')->label('Slug')->required()->maxLength(255),
            FileUpload::make('cover_image')->label('Gambar Sampul Album')->image()->disk(self::disk())->directory('gallery'),
            FileUpload::make('bulk_photos')
                ->label('Unggah Banyak Foto Sekaligus (Bulk Upload)')
                ->helperText('Pilih beberapa foto sekaligus untuk langsung ditambahkan ke album ini.')
                ->image()
                ->multiple()
                ->reorderable()
                ->appendFiles()
                ->maxFiles(30)
                ->disk(self::disk())
                ->directory('gallery/school')
                ->columnSpanFull()
                ->dehydrated(false),
            Textarea::make('description')->label('Deskripsi Album')->rows(3)->columnSpanFull(),
            Select::make('status')->label('Status')->options(self::statusOptions())->default('published')->required(),
        ])->columns(2);
    }

    public static function galleryItemForm(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('gallery_album_id')->label('Album Galeri')->relationship('album', 'title')->required(),
            TextInput::make('title')->label('Judul Foto')->placeholder('Contoh: Kegiatan Upacara Bendera')->maxLength(255),
            FileUpload::make('image')->label('Berkas Foto')->image()->imageEditor()->disk(self::disk())->directory('gallery/school')->required(),
            Textarea::make('caption')->label('Keterangan Foto')->rows(2)->columnSpanFull(),
            TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
            Select::make('status')->label('Status')->options(self::statusOptions())->default('published')->required(),
        ])->columns(2);
    }

    public static function ppdbForm(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('registration_number')->label('Nomor Pendaftaran')->disabled()->dehydrated(false),
            TextInput::make('academic_year')->label('Tahun Ajaran')->disabled()->dehydrated(false),
            TextInput::make('student_name')->label('Nama Calon Siswa')->required(),
            TextInput::make('nik')->label('NIK')->required(),
            TextInput::make('nisn')->label('NISN'),
            TextInput::make('birth_place')->label('Tempat Lahir')->required(),
            DatePicker::make('birth_date')->label('Tanggal Lahir')->required(),
            Select::make('gender')->label('Jenis Kelamin')->options(['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan'])->required(),
            Textarea::make('address')->label('Alamat Lengkap')->rows(3)->columnSpanFull(),
            TextInput::make('previous_school')->label('Asal TK / RA'),
            TextInput::make('father_name')->label('Nama Ayah')->required(),
            TextInput::make('mother_name')->label('Nama Ibu')->required(),
            TextInput::make('guardian_name')->label('Nama Wali'),
            TextInput::make('parent_job')->label('Pekerjaan Orang Tua'),
            TextInput::make('parent_phone')->label('Nomor WhatsApp')->tel()->required(),
            Select::make('status')->label('Status Seleksi / Kelulusan')->options([
                'pending' => 'Menunggu Verifikasi',
                'passed' => 'Lolos / Diterima',
                'failed' => 'Tidak Lolos',
            ])->default('pending')->required(),
        ])->columns(2);
    }

    public static function settingForm(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('key')->label('Nama Pengaturan (Key)')->required()->maxLength(255),
            Select::make('group')
                ->label('Kelompok Kategori (Group)')
                ->options([
                    'general' => 'Umum / Identitas Madrasah',
                    'contact' => 'Kontak & Alamat',
                    'social' => 'Media Sosial',
                    'ppdb' => 'Pengaturan PPDB',
                    'academic' => 'Akademik',
                ])
                ->default('general')
                ->required(),
            Textarea::make('value')->label('Isi Nilai (Value)')->rows(4)->columnSpanFull(),
        ])->columns(2);
    }

    public static function userForm(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nama')->required()->maxLength(255),
            TextInput::make('email')->label('Email')->email()->required()->maxLength(255),
            TextInput::make('password')->label('Kata sandi')->password()->dehydrated(fn ($state) => filled($state))->required(fn (string $operation): bool => $operation === 'create'),
            Select::make('roles')->label('Peran')->relationship('roles', 'name')->multiple()->preload(),
        ])->columns(2);
    }

    public static function basicTable(Table $table, string $mainColumn = 'title'): Table
    {
        $columns = [];
        if ($mainColumn === 'photo') {
            $columns[] = ImageColumn::make('photo')->label('Foto')->circular()->disk(self::disk());
            $columns[] = TextColumn::make('name')->label('Nama Lengkap')->searchable()->sortable();
            $columns[] = TextColumn::make('position')->label('Jabatan')->searchable();
            $columns[] = TextColumn::make('employment_status')->label('Status')->badge();
            $columns[] = TextColumn::make('sort_order')->label('Urutan')->sortable();
        } else {
            $columns[] = TextColumn::make($mainColumn)->label(self::columnLabel($mainColumn))->searchable()->sortable();
        }

        $columns[] = TextColumn::make('status')
            ->label('Status')
            ->formatStateUsing(fn (string $state): string => self::statusOptions()[$state] ?? $state)
            ->badge()
            ->color(fn (string $state): string => match ($state) {
                'published' => 'success',
                'draft' => 'warning',
                'archived' => 'danger',
                default => 'gray',
            })
            ->sortable();

        $columns[] = TextColumn::make('updated_at')->label('Diperbarui')->dateTime('d M Y H:i')->sortable()->toggleable(isToggledHiddenByDefault: true);

        return $table
            ->columns($columns)
            ->filters([
                SelectFilter::make('status')->label('Status')->options(self::statusOptions()),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function ppdbTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('registration_number')->label('No. Daftar')->searchable()->sortable()->copyable(),
                TextColumn::make('student_name')->label('Nama Siswa')->searchable()->sortable(),
                TextColumn::make('gender')->label('L/P')->sortable(),
                TextColumn::make('academic_year')->label('Tahun')->sortable(),
                TextColumn::make('parent_phone')->label('WhatsApp')->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => [
                        'pending' => 'Menunggu',
                        'passed' => 'Lolos',
                        'failed' => 'Tidak Lolos',
                    ][$state] ?? $state)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'passed' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')->label('Tgl Daftar')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Status Seleksi')->options([
                    'pending' => 'Menunggu',
                    'passed' => 'Lolos',
                    'failed' => 'Tidak Lolos',
                ]),
                SelectFilter::make('academic_year')->label('Tahun Ajaran')->options(fn () => \App\Models\PpdbApplicant::query()->distinct()->pluck('academic_year', 'academic_year')->all()),
            ])
            ->recordActions([
                Action::make('whatsapp')
                    ->label('Kirim WA')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(function (\App\Models\PpdbApplicant $record) {
                        $phone = preg_replace('/[^0-9]/', '', (string) $record->parent_phone);
                        if (str_starts_with($phone, '0')) {
                            $phone = '62' . substr($phone, 1);
                        }
                        $statusText = match ($record->status) {
                            'passed' => 'dinyatakan LOLOS / DITERIMA',
                            'failed' => 'dinyatakan TIDAK LOLOS',
                            default => 'sedang dalam proses verifikasi',
                        };
                        $message = "Assalamualaikum Wr. Wb. Bpk/Ibu wali dari ananda {$record->student_name}, pendaftaran PPDB MI Hubbul Wathan dengan Nomor {$record->registration_number} {$statusText}.";
                        return "https://wa.me/{$phone}?text=" . rawurlencode($message);
                    })
                    ->openUrlInNewTab(),
                Action::make('download_documents')
                    ->label('Unduh Berkas')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (\App\Models\PpdbApplicant $record) {
                        $documents = $record->documents;

                        if ($documents->isEmpty()) {
                            Notification::make()
                                ->title('Tidak ada berkas')
                                ->body('Pendaftar ini belum mengunggah berkas dokumen.')
                                ->warning()
                                ->send();
                            return null;
                        }

                        $tempDir = storage_path('app/temp');
                        if (!file_exists($tempDir)) {
                            mkdir($tempDir, 0755, true);
                        }

                        $zipFileName = 'berkas-' . Str::slug($record->registration_number) . '-' . time() . '.zip';
                        $zipFilePath = $tempDir . '/' . $zipFileName;

                        $zip = new \ZipArchive();
                        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                            $addedFiles = 0;
                            foreach ($documents as $doc) {
                                if (Storage::disk('local')->exists($doc->file_path)) {
                                    $fileContents = Storage::disk('local')->get($doc->file_path);
                                    $extension = pathinfo($doc->original_name ?: $doc->file_path, PATHINFO_EXTENSION);
                                    $safeDocName = Str::slug($doc->type) . ($extension ? '.' . $extension : '');
                                    $zip->addFromString($safeDocName, $fileContents);
                                    $addedFiles++;
                                }
                            }
                            $zip->close();

                            if ($addedFiles > 0 && file_exists($zipFilePath)) {
                                return response()->download($zipFilePath, 'berkas-' . $record->registration_number . '.zip')->deleteFileAfterSend(true);
                            }
                        }

                        if (file_exists($zipFilePath)) {
                            @unlink($zipFilePath);
                        }

                        Notification::make()
                            ->title('Berkas tidak ditemukan')
                            ->body('File fisik dokumen tidak ditemukan di penyimpanan server.')
                            ->danger()
                            ->send();

                        return null;
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                Action::make('export_csv')
                    ->label('Export Data CSV/Excel')
                    ->icon('heroicon-o-arrow-down-on-square-stack')
                    ->color('success')
                    ->action(function () {
                        $applicants = \App\Models\PpdbApplicant::query()->latest()->get();
                        $headers = [
                            'Content-Type' => 'text/csv',
                            'Content-Disposition' => 'attachment; filename="data-pendaftar-ppdb-' . date('Y-m-d') . '.csv"',
                        ];

                        $callback = function () use ($applicants) {
                            $file = fopen('php://output', 'w');
                            fputcsv($file, ['No Pendaftaran', 'Tahun Ajaran', 'Nama Siswa', 'NIK', 'NISN', 'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin', 'Alamat', 'Asal Sekolah', 'Nama Ayah', 'Nama Ibu', 'Nama Wali', 'Pekerjaan', 'No WhatsApp', 'Status', 'Tanggal Daftar']);

                            foreach ($applicants as $app) {
                                fputcsv($file, [
                                    $app->registration_number,
                                    $app->academic_year,
                                    $app->student_name,
                                    $app->nik,
                                    $app->nisn,
                                    $app->birth_place,
                                    $app->birth_date,
                                    $app->gender,
                                    $app->address,
                                    $app->previous_school,
                                    $app->father_name,
                                    $app->mother_name,
                                    $app->guardian_name,
                                    $app->parent_job,
                                    $app->parent_phone,
                                    $app->status,
                                    $app->created_at,
                                ]);
                            }
                            fclose($file);
                        };

                        return response()->stream($callback, 200, $headers);
                    }),
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function ppdbDocumentForm(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('ppdb_applicant_id')->label('Pendaftar')->relationship('applicant', 'registration_number')->required(),
            TextInput::make('type')->label('Jenis dokumen')->required(),
            FileUpload::make('file_path')->label('Berkas')->disk('local')->directory('ppdb')->visibility('private')->required(),
            TextInput::make('original_name')->label('Nama berkas asli'),
            TextInput::make('mime_type')->label('Tipe berkas'),
            TextInput::make('file_size')->label('Ukuran berkas')->numeric(),
        ])->columns(2);
    }

    public static function ppdbDocumentTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('applicant.registration_number')->label('Nomor pendaftaran')->searchable(),
                TextColumn::make('type')->label('Jenis dokumen')->searchable()->sortable(),
                TextColumn::make('original_name')->label('Nama berkas asli')->searchable(),
                TextColumn::make('mime_type')->label('Tipe berkas'),
                TextColumn::make('created_at')->label('Tanggal unggah')->dateTime()->sortable(),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function userTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('roles.name')->label('Peran')->badge(),
                TextColumn::make('updated_at')->label('Terakhir diperbarui')->dateTime()->sortable(),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function activityLogTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event')->label('Aksi')->badge()->sortable(),
                TextColumn::make('description')->label('Deskripsi')->searchable(),
                TextColumn::make('causer.name')->label('Pengguna')->searchable(),
                TextColumn::make('subject_type')->label('Subjek')->toggleable(),
                TextColumn::make('created_at')->label('Waktu')->dateTime()->sortable(),
            ]);
    }

    public static function columnLabel(string $column): string
    {
        return match ($column) {
            'title' => 'Judul',
            'name' => 'Nama',
            'starts_at' => 'Waktu Mulai',
            'registration_number' => 'Nomor Pendaftaran',
            'key' => 'Kunci',
            default => str_replace('_', ' ', ucfirst($column)),
        };
    }
}

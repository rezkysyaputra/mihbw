<?php

namespace App\Filament\Resources\HomepageImages\Pages;

use App\Filament\Resources\HomepageImages\HomepageImageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHomepageImage extends EditRecord
{
    protected static string $resource = HomepageImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\HomepageImages\Pages;

use App\Filament\Resources\HomepageImages\HomepageImageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHomepageImage extends CreateRecord
{
    protected static string $resource = HomepageImageResource::class;
}

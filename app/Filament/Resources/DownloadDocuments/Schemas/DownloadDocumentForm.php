<?php

namespace App\Filament\Resources\DownloadDocuments\Schemas;

use App\Filament\Support\PortalResourceKit;
use Filament\Schemas\Schema;

class DownloadDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return PortalResourceKit::documentForm($schema);
    }
}

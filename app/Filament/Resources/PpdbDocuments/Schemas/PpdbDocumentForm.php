<?php

namespace App\Filament\Resources\PpdbDocuments\Schemas;

use App\Filament\Support\PortalResourceKit;
use Filament\Schemas\Schema;

class PpdbDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return PortalResourceKit::ppdbDocumentForm($schema);
    }
}

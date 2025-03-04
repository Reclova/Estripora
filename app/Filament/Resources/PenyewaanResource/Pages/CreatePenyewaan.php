<?php

namespace App\Filament\Resources\PenyewaanResource\Pages;

use App\Filament\Resources\PenyewaanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenyewaan extends CreateRecord
{
    protected static string $resource = PenyewaanResource::class;

    protected static ?string $title = 'Tambah Penyewaan';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

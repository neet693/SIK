<?php

namespace App\Filament\Resources\LoanerResource\Pages;

use App\Filament\Resources\LoanerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoaner extends EditRecord
{
    protected static string $resource = LoanerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

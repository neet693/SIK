<?php

namespace App\Filament\Resources\LoanerResource\Pages;

use App\Filament\Resources\LoanerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoaners extends ListRecords
{
    protected static string $resource = LoanerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

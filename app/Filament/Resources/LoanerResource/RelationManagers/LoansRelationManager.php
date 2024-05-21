<?php

namespace App\Filament\Resources\LoanerResource\RelationManagers;

use App\Models\Loan;
use App\Models\LoanType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoansRelationManager extends RelationManager
{
    protected static string $relationship = 'loans';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('loaner_id')
                    ->relationship('loaner', 'name')
                    ->required(),
                Forms\Components\Select::make('loan_type_id')
                    ->relationship('loan_type', 'name')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('term')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('loan_start_date')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('loaner.name')
                    ->label('Loaner')
                    ->sortable(),
                Tables\Columns\TextColumn::make('loan_type.name')
                    ->label('Tipe')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('term')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('loan_start_date')
                    ->label('Tanggal Pinjam')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('loan.pokok')
                    ->money('IDR', locale: 'id')
                    ->label('Pokok')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $record->amount / $record->term;
                    }),
                Tables\Columns\TextColumn::make('loan.bunga')
                    ->money('IDR', locale: 'id')
                    ->label('Bunga')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        $loanType = LoanType::find($record->loan_type_id);
                        return ($record->amount * $loanType->interest_rate / 100);
                    }),
                Tables\Columns\TextColumn::make('loan.TotalBayar')
                    ->money('IDR', locale: 'id')
                    ->label('Total Bayar')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        $loanType = LoanType::find($record->loan_type_id);
                        $pokok = $record->amount / $record->term;
                        $bunga = $record->amount * $loanType->interest_rate / 100;
                        return $pokok + $bunga;
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function installmentsTable(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Column::make('number')->label('Angsuran Ke')->sortable(),
                Tables\Columns\Column::make('principal')->label('Pokok')->sortable(),
                Tables\Columns\Column::make('total_payment')->label('Total Pembayaran')->sortable(),
                Tables\Columns\Column::make('due_date')->label('Tanggal Jatuh Tempo')->date('d M Y')->sortable(),
                Tables\Columns\Column::make('interest')->label('Bunga')->sortable(),
            ]);
    }
}

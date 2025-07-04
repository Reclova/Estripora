<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Lokasi;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LokasiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LokasiResource\RelationManagers;

class LokasiResource extends Resource
{
    protected static ?string $model = Lokasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function getNavigationLabel(): string
    {
        return 'Lokasi';
    }
    public static function getPluralLabel(): string
    {
        return 'Lokasi';
    }
    public static function getModelLabel(): string
    {
        return 'Lokasi';
    }
    public static function getnavigationGroup(): ?string
    {
        return 'Kelola Tempat & Area';
    }
    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public static function canView(Model $record): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Grid::make()
                        ->schema([
                            Select::make('id_tempat')
                                ->label('Pilih Tempat')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->disabled(fn(string $operation): bool => $operation === 'edit')
                                ->relationship('tempat', 'nama'),
                            TextInput::make('nama_lokasi')
                                ->label('Nama Lokasi')
                                ->required()
                                ->rules([
                                    function (callable $get) {
                                        return Rule::unique('lokasi', 'nama_lokasi')
                                            ->where('id_tempat', $get('id_tempat'))
                                            ->ignore($get('id_lokasi'), 'id_lokasi');
                                    },
                                ])
                                ->validationMessages([
                                    'unique' => 'Lokasi yang sama sudah dibuat untuk tempat ini.',
                                ]),
                            TextInput::make('tarif')
                                ->label('Tarif')
                                ->numeric()
                                ->prefix('Rp')
                                ->required(),
                        ])
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tempat.nama')->label('Nama Tempat')->sortable()->searchable(),
                TextColumn::make('nama_lokasi')->label('Nama Lokasi')->sortable()->searchable(),
                TextColumn::make('tarif')
                    ->label('Tarif')
                    ->formatStateUsing(fn(string $state): string => 'Rp ' . number_format($state, 2, ',', '.'))
                    ->sortable(),

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                // Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLokasis::route('/'),
            'create' => Pages\CreateLokasi::route('/create'),
            'edit' => Pages\EditLokasi::route('/{record}/edit'),
        ];
    }
}

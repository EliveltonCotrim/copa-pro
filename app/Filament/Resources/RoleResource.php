<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Components\{TextInput, Select};
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationLabel = 'Papéis';
    protected static ?string $pluralLabel = 'Papéis';
    protected static ?string $label = 'Papel';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Controle de acesso';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->unique(ignoreRecord: true)
                    ->readOnly(fn(string $context): bool => $context !== 'create')
                    ->required()
                    ->maxLength(255),
                Select::make('permissions')
                    ->multiple()
                    ->relationship(titleAttribute: 'name')
                    ->preload()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label("Nome"),
                // TextColumn::make('permissions')
                //     ->label('Permissões')
                //     ->formatStateUsing(function ($record) {
                //         if ($record->permissions->isNotEmpty()) {
                //             return $record->permissions->pluck('name')->join(' ');
                //         }
                //         return 'Sem permissões';
                //     })->badge()
                //     ->searchable()
                //     ->placeholder('Sem permissões'),
                // ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRoles::route('/'),
        ];
    }
}

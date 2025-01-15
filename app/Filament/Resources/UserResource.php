<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Organizer;
use App\Models\User;
use App\RoleEnum;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Usuários';
    protected static ?string $label = 'Usuário';
    protected static ?string $pluralLabel = 'Usuários';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nome')
                    ->string()
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('E-mail')
                    ->unique(ignoreRecord: true)
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('password')
                    ->required()
                    ->label('Senha')
                    ->minLength(8)
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create')
                    ->maxLength(255),
                Select::make('roles')
                    ->options(RoleEnum::class)
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if ($state === RoleEnum::ORGANIZATION->value) {
                            $set('phone', null);
                        }
                    })
                    ->in(RoleEnum::cases())
                    ->required()
                    ->label('Função'),
                TextInput::make('phone')
                    ->visible(function (Get $get) {
                        return $get('roles') == RoleEnum::ORGANIZATION->value;
                    })
                    ->label('Telefone')
                    ->mask('(99) 99999-9999')
                    ->placeholder('(99) 99999-9999')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                // Select::make('roles')
                //     ->multiple()
                //     ->relationship(titleAttribute: 'name')
                //     ->preload()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('userable_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('userable_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles')
                    ->label('Funções')
                    ->sortable(),

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(function ($record) {
                        return Notification::make()
                            ->success()
                            ->title('Desativado com Sucesso')
                            ->message("O usuário <strong>{$record->name}</strong> foi desativado com sucesso.");
                    }),
                Tables\Actions\RestoreAction::make()
                    ->successNotification(function ($record) {
                        return Notification::make()
                            ->success()
                            ->title("Restaurado com Sucesso")
                            ->body("O usuário <strong>{$record->name}</strong> foi restaurado com sucesso");
                    })
                    ->visible(fn($record) => $record->trashed()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

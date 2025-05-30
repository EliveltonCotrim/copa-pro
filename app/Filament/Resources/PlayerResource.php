<?php

namespace App\Filament\Resources;

use App\Enum\{PlayerExperienceLevelEnum, PlayerPlatformGameEnum, PlayerSexEnum, PlayerStatusEnum};
use App\Filament\Resources\PlayerResource\Pages;
use App\Models\Player;
use Filament\Forms\Components\{DatePicker, Grid, Group, Select, TextInput, Textarea};
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};
use Illuminate\Support\Facades\Hash;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class PlayerResource extends Resource
{
    protected static ?string $model = Player::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Jogadores';

    protected static ?string $label = 'Jogador';

    protected static ?string $pluralLabel = 'Jogadores';

    protected static ?string $recordTitleAttribute = 'nickname';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->relationship('user')->schema([
                    Grid::make(3)->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('E-mail')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255),
                        TextInput::make('password')
                            ->revealable()
                            ->password()
                            ->label('Senha')
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->minLength(6)
                            ->maxLength(255),
                    ]),
                ])->columnSpanFull(),
                TextInput::make('nickname')
                    ->label('Nickname do jogador')
                    ->maxLength(50),
                TextInput::make('heart_team_name')
                    ->label('Time do coração')
                    ->maxLength(255),
                DatePicker::make('birth_dt')
                    ->maxDate(now()->format('Y-m-d'))
                    ->label('Data de nascimento'),
                Select::make('sex')
                    ->options(PlayerSexEnum::class)
                    ->searchable()
                    ->label('Gênero')
                    ->required(),
                PhoneInput::make('phone')
                    ->label('WhatsApp')
                    ->required(),
                Select::make('level_experience')
                    ->label('Nível de experiência')
                    ->options(PlayerExperienceLevelEnum::class)
                    ->searchable()
                    ->required(),
                Textarea::make('bio')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(PlayerStatusEnum::class)
                    ->required(),
                Select::make('game_platform')
                    ->label('Plataforma de jogo')
                    ->options(PlayerPlatformGameEnum::class)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('nickname')
                    ->label('Nickname')
                    ->searchable()
                    ->placeholder('Sem nickname'),
                TextColumn::make('birth_dt')
                    ->label('Data de nascimento')
                    ->dateTime('d/m/Y')
                    ->sortable(),
                TextColumn::make('sex')
                    ->label('Gênero'),
                TextColumn::make('phone')
                    ->label('WhatsApp')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status'),
                TextColumn::make('game_platform')
                    ->Label('Plataforma de jogo'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label('Inativado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make()->before(function ($record, \Filament\Tables\Actions\DeleteAction $action) {

                    // Verifica se há alguma inscrição ativa
                    if ($record->hasActiveChampionships()) {
                        // Impede a exclusão e exibe um erro
                        Notification::make()
                            ->warning()
                            ->title('Atenção!')
                            ->body("<strong>{$record->user->name}</strong> está inscrito(a) em campeonatos ativos ou em andamento e não pode ser excluído(a).")
                            ->send();

                        $action->cancel();
                    }
                }),
                Tables\Actions\RestoreAction::make()
                    ->successNotification(function ($record) {
                        return Notification::make()
                            ->success()
                            ->title('Player restaurado(a)')
                            ->body("<strong>{$record->user->name}</strong> está restaurado(a).");
                    })
                    ->visible(fn ($record) => $record->trashed()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    // Tables\Actions\ForceDeleteBulkAction::make(),
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
            'index'  => Pages\ListPlayers::route('/'),
            'create' => Pages\CreatePlayer::route('/create'),
            'edit'   => Pages\EditPlayer::route('/{record}/edit'),
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

<?php

namespace App\Filament\Resources;

use App\Enum\PaymentStatusEnum;
use App\Enum\PlayerPlatformGameEnum;
use App\Enum\PlayerSexEnum;
use App\Enum\PlayerStatusEnum;
use App\Filament\Resources\PlayerResource\Pages;
use App\Filament\Resources\PlayerResource\RelationManagers;
use App\Models\Player;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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
                    TextInput::make('name')
                        ->label('Nome')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->label('E-mail')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('password')
                        ->revealable()
                        ->password()
                        ->label('Senha')
                        ->required()
                        ->minLength(6)
                        ->maxLength(255),
                ])->columnSpanFull(),
                TextInput::make('nickname')
                    ->label('Nickname do Jogador')
                    ->maxLength(50),
                TextInput::make('heart_team_name')
                    ->label('Time do Coração')
                    ->maxLength(255),
                DatePicker::make('birth_dt')
                    ->maxDate(now()->format('Y-m-d'))
                    ->label('Data de Nascimento'),
                Select::make('sex')
                    ->options(PlayerSexEnum::class)
                    ->searchable()
                    ->label('Sexo')
                    ->required(),
                PhoneInput::make('phone')
                    ->label('WhatsApp')
                    ->required(),
                Textarea::make('bio')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(PlayerStatusEnum::class)
                    ->required(),
                Select::make('game_platform')
                    ->label('Plataforma de Jogo')
                    ->options(PlayerPlatformGameEnum::class)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nickname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('heart_team_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birth_dt')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sex'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('game_platform'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
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
                            ->title('warning')
                            ->body('Este jogador está inscrito em campeonatos ativos ou em andamento e não pode ser excluído.')
                            ->warning()
                            ->send();

                        $action->cancel();
                    }
                }),
                Tables\Actions\RestoreAction::make(),

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
            'index' => Pages\ListPlayers::route('/'),
            'create' => Pages\CreatePlayer::route('/create'),
            'edit' => Pages\EditPlayer::route('/{record}/edit'),
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

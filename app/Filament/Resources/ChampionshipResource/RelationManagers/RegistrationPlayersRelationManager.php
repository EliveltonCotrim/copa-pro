<?php

namespace App\Filament\Resources\ChampionshipResource\RelationManagers;

use App\Enum\PlayerPlatformGameEnum;
use App\Enum\PlayerSexEnum;
use App\Enum\RegistrationPlayerStatusEnum;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class RegistrationPlayersRelationManager extends RelationManager
{
    protected static string $relationship = 'RegistrationPlayers';
    protected static ?string $title = 'Inscrições';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('E-mail')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->email()
                    ->maxLength(255),
                TextInput::make('heart_team_name')
                    ->label('Nome do Time do Coração')
                    ->maxLength(255),
                TextInput::make('championship_team_name')
                    ->label('Nome do Time do Campeonato')
                    ->required()
                    ->maxLength(255),
                PhoneInput::make('wpp_number')->required()
                    ->defaultCountry('BR')
                    ->label('WhatsApp'),
                DatePicker::make('birth_dt')
                    ->label('Data de Nascimento')
                    ->maxDate(now()->subYears(13)->format('Y-m-d')),
                Select::make('sex')
                    ->options(PlayerSexEnum::class)
                    ->searchable()
                    ->required()
                    ->label('Sexo'),
                Select::make('game_platform')
                    ->options(PlayerPlatformGameEnum::class)
                    ->searchable()
                    ->required()
                    ->label('Plataforma de Jogo'),
                Select::make('status')
                    ->options(RegistrationPlayerStatusEnum::class)
                    ->searchable()
                    ->required()
                    ->label('Status'),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->label('Nome'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('heart_team_name')->searchable()->label('Time do Coração'),
                Tables\Columns\TextColumn::make('wpp_number')->label('WhatsApp'),
                Tables\Columns\TextColumn::make('status')->label('Status')
                ->toggleable()->badge(),

            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make()->label('Cadastrar Jogador'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}

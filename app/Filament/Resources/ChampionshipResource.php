<?php

namespace App\Filament\Resources;

use App\Enum\ChampionshipFormatEnum;
use App\Enum\ChampionshipGamesEnum;
use App\Enum\ChampionshipStatusEnum;
use App\Enum\PlayerPlatformGameEnum;
use App\Filament\Resources\ChampionshipResource\Pages;
use App\Filament\Resources\ChampionshipResource\RelationManagers;
use App\Models\Championship;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Leandrocfe\FilamentPtbrFormFields\Money;

class ChampionshipResource extends Resource
{
    protected static ?string $model = Championship::class;

    protected static ?string $label = 'Campeonatos';

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                Money::make('registration_fee')
                    ->label('Taxa de inscrição')
                    ->required()
                    ->default('0,00'),
                Forms\Components\Textarea::make('description')
                    ->label('Descrição')
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Data de início')
                    ->minDate(now()->format('Y-m-d'))
                    ->beforeOrEqual('end_date')
                    ->validationMessages([
                        'min_date' => 'A data de início deve ser igual ou posterior à data atual.',
                        'before_or_equal' => 'A data de início deve ser igual ou anterior à data de término.',
                    ])
                    ->date()
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->label('Data de término')
                    ->required()
                    ->afterOrEqual('start_date')
                    ->validationMessages([
                        'after_or_equal' => 'A data de término deve ser igual ou posterior à data de início.',
                    ])
                    ->date(),
                Forms\Components\FileUpload::make('banner_path')
                    ->image()
                    ->live()
                    ->imageEditor()
                    ->preserveFilenames()
                    ->previewable()
                    ->maxSize(2048)
                    ->directory('banners-championships')
                    ->label('Banner'),
                Forms\Components\FileUpload::make('regulation_path')
                    ->label('Regulamento')
                    ->preserveFilenames()
                    ->directory('regulations-championships')
                    ->acceptedFileTypes(['application/pdf']),
                Forms\Components\Select::make('game_platform')
                    ->options(PlayerPlatformGameEnum::class)
                    ->searchable()
                    ->required()
                    ->label('Plataforma do jogo'),
                Forms\Components\Select::make('game')
                    ->options(ChampionshipGamesEnum::class)
                    ->searchable()
                    ->required()
                    ->label('Jogo'),
                Forms\Components\Select::make('championship_format')
                    ->options(ChampionshipFormatEnum::class)
                    ->searchable()
                    ->required()
                    ->live()
                    ->label('Formato do campeonato'),
                Forms\Components\Select::make('max_playes')
                    ->visible(fn(Get $get): bool => $get('championship_format') == ChampionshipFormatEnum::CUP->value)
                    ->options([
                        '8' => '8',
                        '16' => '16',
                        '32' => '32',
                        '64' => '64',
                    ])->label('Número máximo de jogadores')->required(),
                Forms\Components\Select::make('max_playes')
                    ->visible(function (Get $get, Set $set) {
                        return $get('championship_format') == ChampionshipFormatEnum::KNOCKOUT->value;
                    })
                    ->options([
                        '16' => '16',
                        '8' => '8',
                        '4' => '4',
                        '2' => '2',
                    ])->label('Número máximo de jogadores')
                    ->helperText('Oitavas, quartas, semifinal, ou final')->required(),
                Forms\Components\TextInput::make('max_playes')
                    ->visible(fn(Get $get): bool => $get('championship_format') == ChampionshipFormatEnum::LEAGUE->value)
                    ->label('Número máximo de jogadores')
                    ->maxValue(32)
                    ->minValue(2)
                    ->numeric()
                    ->required()
                    ->minValue(0),
                Forms\Components\TextInput::make('wpp_group_link')
                    ->label('Link do grupo de WhatsApp')
                    ->url()
                    ->prefix('https://')
                    ->maxLength(255),
                // Forms\Components\TextInput::make('registration_link')
                //     ->url()
                //     ->prefix('https://')
                //     ->label('Link de inscrição')
                //     ->maxLength(255),
                Forms\Components\Textarea::make('information')
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options(ChampionshipStatusEnum::class)
                    ->in(ChampionshipStatusEnum::cases())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Data de início')
                    ->date()
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration_fee')
                    ->label('Taxa de inscrição')
                    ->toggleable()
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->badge(),
            ])->defaultSort('start_date')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListChampionships::route('/'),
            'create' => Pages\CreateChampionship::route('/create'),
            'view' => Pages\ViewChampionship::route('/{record}'),
            'edit' => Pages\EditChampionship::route('/{record:uuid}/edit'),
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

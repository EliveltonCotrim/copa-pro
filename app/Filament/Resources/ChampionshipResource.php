<?php

namespace App\Filament\Resources;

use App\Enum\{ChampionshipFormatEnum, ChampionshipGamesEnum, ChampionshipStatusEnum, PlayerPlatformGameEnum};
use App\Filament\Resources\ChampionshipResource\Pages;
use App\Filament\Resources\ChampionshipResource\RelationManagers;
use App\Filament\Resources\ChampionshipResource\RelationManagers\RegistrationPlayerRelationManager;
use App\Filament\Resources\ChampionshipResource\RelationManagers\RegistrationPlayersRelationManager;
use App\Models\Championship;
use Closure;
use Filament\Forms;
use Filament\Forms\{Form, Get, Set};
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};
use Filament\Forms\Components\{Select, TextInput, DatePicker, Textarea, FileUpload};
use Filament\Tables\Columns\TextColumn;
use Leandrocfe\FilamentPtbrFormFields\Money;
use Filament\Notifications\Notification;

class ChampionshipResource extends Resource
{
    protected static ?string $model = Championship::class;

    protected static ?string $navigationLabel = 'Campeonatos';

    protected static ?string $label = 'Campeonato';

    protected static ?string $pluralLabel = 'Campeonatos';

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nome')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Money::make('registration_fee')
                    ->label('Taxa de inscrição')
                    ->required()
                    ->default('0,00'),
                RichEditor::make('description')
                    ->label('Descrição'),
                Textarea::make('information')
                    ->label('Informação'),
                DatePicker::make(name: 'start_date')
                    ->label('Data de início')
                    ->minDate(fn(string $context): string|null => $context == "create" ? now()->format('Y-m-d') : null)
                    ->beforeOrEqual('end_date')
                    ->validationMessages([
                        'min_date' => 'A data de início deve ser igual ou posterior à data atual.',
                        'before_or_equal' => 'A data de início deve ser igual ou anterior à data de término.',
                    ])
                    ->date()
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Data de término')
                    ->required()
                    ->afterOrEqual('start_date')
                    ->validationMessages([
                        'after_or_equal' => 'A data de término deve ser igual ou posterior à data de início.',
                    ])
                    ->date(),
                SpatieMediaLibraryFileUpload::make('banner_path')
                    ->image()
                    ->live()
                    ->helperText('Dimensão ideal: 1280px × 192px')
                    ->imageEditor()
                    ->preserveFilenames()
                    ->previewable()
                    ->optimize('webp')
                    ->maxSize(2048)
                    ->directory('banners-championships')
                    ->label('Banner'),
                FileUpload::make('regulation_path')
                    ->label('Regulamento')
                    ->preserveFilenames()
                    ->directory('regulations-championships')
                    ->acceptedFileTypes(['application/pdf']),
                Select::make('game_platform')
                    ->options(PlayerPlatformGameEnum::class)
                    ->searchable()
                    ->required()
                    ->label('Plataforma do jogo'),
                Select::make('game')
                    ->options(ChampionshipGamesEnum::class)
                    ->searchable()
                    ->required()
                    ->label('Jogo'),
                Select::make('championship_format')
                    ->options(ChampionshipFormatEnum::class)
                    ->searchable()
                    ->required()
                    ->live()
                    ->label('Formato do campeonato'),
                Select::make('max_playes')
                    ->visible(fn(Get $get): bool => $get('championship_format') == ChampionshipFormatEnum::CUP->value)
                    ->options([
                        '8' => '8',
                        '16' => '16',
                        '32' => '32',
                        '64' => '64',
                    ])->label('Número máximo de jogadores')->required(),
                Select::make('max_playes')
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
                TextInput::make('max_playes')
                    ->visible(fn(Get $get): bool => $get('championship_format') == ChampionshipFormatEnum::LEAGUE->value)
                    ->label('Número máximo de jogadores')
                    ->maxValue(32)
                    ->minValue(2)
                    ->numeric()
                    ->required()
                    ->minValue(0),
                TextInput::make('wpp_group_link')
                    ->label('Link do grupo de WhatsApp')
                    ->url()
                    ->prefix('https://')
                    ->maxLength(255),
                Select::make('status')
                    ->options(ChampionshipStatusEnum::class)
                    ->in(ChampionshipStatusEnum::cases())
                    ->required(),
                // TextInput::make('registration_link')
                //     ->url()
                //     ->prefix('https://')
                //     ->label('Link de inscrição')
                //     ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('banner_path')->label('Banner')->size('55px'),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label('Data de início')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Data de término')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('registration_fee')
                    ->label('Taxa de inscrição')
                    ->toggleable()
                    ->money('BRL')
                    ->sortable(),
                SelectColumn::make('status')
                    ->options(ChampionshipStatusEnum::class)
                    ->label('Status')->rules(['required']),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->visible(fn($record) => !$record->trashed()),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(function ($record) {
                        return Notification::make()
                            ->warning()
                            ->title("Campeonato desativado")
                            ->body("<strong>{$record->name}</strong> está na lixeira.");
                    }),
                Tables\Actions\RestoreAction::make()
                    ->successNotification(function ($record) {
                        return Notification::make()
                            ->success()
                            ->title("Campeonato restaurado")
                            ->body("<strong>{$record->name}</strong> está restaurado.");
                    })
                    ->visible(fn($record) => $record->trashed()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    //Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('created_at')
                    ->label('Data de criação')
                    ->date()
                    ->collapsible(),
            ])
            ->defaultSort('start_date');
    }

    public static function getRelations(): array
    {
        return [
            RegistrationPlayersRelationManager::class,
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

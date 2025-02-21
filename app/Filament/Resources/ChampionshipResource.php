<?php

namespace App\Filament\Resources;

use App\Enum\{ChampionshipFormatEnum, ChampionshipGamesEnum, ChampionshipStatusEnum, PlayerPlatformGameEnum};
use App\Filament\Resources\ChampionshipResource\Pages;
use App\Filament\Resources\ChampionshipResource\RelationManagers;
use App\Filament\Resources\ChampionshipResource\RelationManagers\RegistrationPlayerRelationManager;
use App\Filament\Resources\ChampionshipResource\RelationManagers\RegistrationPlayersRelationManager;
use App\Models\{Championship, UF};
use Closure;
use Filament\Forms;
use Filament\Forms\{Form, Get, Set};
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\{SelectColumn, TextColumn, SpatieMediaLibraryImageColumn};
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};
use Filament\Forms\Components\{Select, Group, Hidden, TextInput, DatePicker, Textarea, FileUpload, Grid, RichEditor};
use Leandrocfe\FilamentPtbrFormFields\{Money, Cep};
use Filament\Notifications\Notification;
use Filament\Forms\Components\Wizard;

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
        return $form->schema([
            Wizard::make([
                Wizard\Step::make('Descrição')
                    ->schema([
                        Grid::make(['default' => 1, 'lg' => 2])->schema([
                            TextInput::make('name')
                                ->label('Nome')
                                ->unique(ignoreRecord: true)
                                ->required()
                                ->maxLength(255),
                            Money::make('registration_fee')
                                ->label('Taxa de inscrição')
                                ->required()
                                ->default('0,00'),
                        ]),
                        Grid::make(['default' => 1, 'lg' => 2])->schema([
                            RichEditor::make('description')
                                ->label('Descrição'),
                            RichEditor::make('information')
                                ->label('Informação'),
                        ]),
                        Grid::make(['default' => 1, 'lg' => 2])->schema([
                            DatePicker::make('start_date')
                                ->label('Data de início')
                                ->live()
                                ->minDate(fn($record) => $record ? $record->start_date : now()->format('Y-m-d'))
                                ->beforeOrEqual('end_date')
                                ->validationMessages([
                                    'min_date' => 'A data de início deve ser igual ou posterior à data atual.',
                                    'before_or_equal' => 'A data de início deve ser igual ou anterior à data de término.',
                                ])
                                ->required(),
                            DatePicker::make('end_date')
                                ->label('Data de término')
                                ->minDate(fn(callable $get) => $get('start_date') ?: now()->format('Y-m-d'))
                                ->required()
                                ->afterOrEqual('start_date')
                                ->validationMessages([
                                    'after_or_equal' => 'A data de término deve ser igual ou posterior à data de início.',
                                ]),
                        ]),
                        Grid::make(['default' => 1, 'lg' => 2])->schema([
                            SpatieMediaLibraryFileUpload::make('banner_path')
                                ->image()
                                ->preserveFilenames()
                                ->helperText('Dimensão ideal: 1280px × 192px')
                                ->previewable()
                                ->maxSize(2048)
                                ->directory('banners-championships')
                                ->label('Banner'),
                            FileUpload::make('regulation_path')
                                ->label('Regulamento')
                                ->preserveFilenames()
                                ->directory('regulations-championships')
                                ->acceptedFileTypes(['application/pdf']),
                        ]),
                    ]),

                Wizard\Step::make('Formato')
                    ->schema([
                        Grid::make(['default' => 1, 'lg' => 2])->schema([
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
                        ]),
                        Select::make('championship_format')
                            ->options(ChampionshipFormatEnum::class)
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (callable $set, $state) {
                                $set('max_players', null);
                            })
                            ->label('Formato do campeonato'),
                        Select::make('max_players')
                            ->visible(fn(Get $get) => $get('championship_format') === ChampionshipFormatEnum::CUP->value)
                            ->options([
                                '8' => '8',
                                '16' => '16',
                                '32' => '32',
                                '64' => '64',
                            ])
                            ->label('Número máximo de jogadores')->required(),
                        Select::make('max_players')
                            ->visible(fn(Get $get) => $get('championship_format') === ChampionshipFormatEnum::KNOCKOUT->value)
                            ->options([
                                '16' => '16',
                                '8' => '8',
                                '4' => '4',
                                '2' => '2',
                            ])
                            ->label('Número máximo de jogadores')
                            ->helperText('Oitavas, quartas, semifinal ou final')
                            ->required(),
                        TextInput::make('max_players')
                            ->visible(fn(Get $get) => $get('championship_format') === ChampionshipFormatEnum::LEAGUE->value)
                            ->label('Número máximo de jogadores')
                            ->numeric()
                            ->maxValue(32)
                            ->minValue(2)
                            ->required(),
                        Grid::make(['default' => 1, 'lg' => 2])->schema([
                            TextInput::make('wpp_group_link')
                                ->label('Link do grupo de WhatsApp')
                                ->url()
                                ->prefix('https://')
                                ->maxLength(255),
                            Select::make('status')
                                ->options(ChampionshipStatusEnum::class)
                                ->required()
                                ->label('Status'),
                        ]),
                    ]),

                Wizard\Step::make('Endereço')
                    ->schema([
                        Group::make()->relationship('address')->schema([
                            Hidden::make('championship_id')
                                ->default(fn(callable $get) => $get('id'))
                                ->disabled(),
                            Grid::make(['default' => 1, 'lg' => 3])->schema([
                                Cep::make('postal_code')
                                    ->required()
                                    ->live()
                                    ->label('CEP')
                                    ->mask('99999-999')
                                    ->helperText('Digite um CEP válido e clique sobre a lupa')
                                    ->viaCep(
                                        mode: 'suffix',
                                        errorMessage: 'CEP inválido.',
                                        setFields: [
                                            'state' => 'uf',
                                            'city' => 'localidade',
                                            'neighborhood' => 'bairro',
                                            'street' => 'logradouro',
                                        ]
                                    ),
                                Select::make('state')
                                    ->required()
                                    ->label('UF')
                                    ->options(UF::all()->pluck('state', 'acronym'))
                                    ->rules('exists:ufs,acronym'),
                                TextInput::make('city')
                                    ->required()
                                    ->label('Cidade'),
                            ]),
                            Grid::make(['default' => 1, 'lg' => 2])->schema([
                                TextInput::make('neighborhood')
                                    ->label('Bairro')
                                    ->required(),
                                TextInput::make('street')
                                    ->label('Rua')
                                    ->required(),
                            ]),
                            Grid::make(['default' => 1, 'lg' => 2])->schema([
                                TextInput::make('number')
                                    ->label('Número')
                                    ->required()
                                    ->integer()
                                    ->mask('9999'),
                                TextInput::make('complement')
                                    ->label('Complemento'),
                            ]),
                        ]),
                    ]),
            ])->columnSpan(['lg' => 3]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('banner_path')
                    ->label('Banner')
                    ->size('55px')
                    ->placeholder("Sem banner"),
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
                    ->label('Status')
                    ->rules(['required'])
                    ->selectablePlaceholder(false),
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

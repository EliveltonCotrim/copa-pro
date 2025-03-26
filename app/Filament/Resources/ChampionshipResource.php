<?php

namespace App\Filament\Resources;

use App\Enum\ChampionshipFormatEnum;
use App\Enum\ChampionshipGamesEnum;
use App\Enum\ChampionshipStatusEnum;
use App\Enum\PlayerPlatformGameEnum;
use App\Filament\Resources\ChampionshipResource\Pages;
use App\Filament\Resources\ChampionshipResource\RelationManagers\RegistrationPlayersRelationManager;
use App\Models\Championship;
use App\Models\UF;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Leandrocfe\FilamentPtbrFormFields\Cep;
use Leandrocfe\FilamentPtbrFormFields\Money;

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
                                ->minDate(fn ($record) => $record ? $record->start_date : now()->format('Y-m-d'))
                                ->beforeOrEqual('end_date')
                                ->validationMessages([
                                    'min_date' => 'A data de início deve ser igual ou posterior à data atual.',
                                    'before_or_equal' => 'A data de início deve ser igual ou anterior à data de término.',
                                ])
                                ->required(),
                            DatePicker::make('end_date')
                                ->label('Data de término')
                                ->minDate(fn (callable $get) => $get('start_date') ?: now()->format('Y-m-d'))
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
                            ->visible(function (Get $get): bool {
                                return (int) $get('championship_format') === ChampionshipFormatEnum::CUP->value;
                            })
                            ->options([
                                '8' => '8',
                                '16' => '16',
                                '32' => '32',
                                '64' => '64',
                            ])
                            ->label('Número máximo de jogadores')->required(),
                        Select::make('max_players')
                            ->visible(fn (Get $get) => (int) $get('championship_format') === ChampionshipFormatEnum::KNOCKOUT->value)
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
                            ->visible(fn (Get $get) => (int) $get('championship_format') === ChampionshipFormatEnum::LEAGUE->value)
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
                                ->default(fn (callable $get) => $get('id'))
                                ->disabled(),
                            Grid::make(['default' => 1, 'lg' => 3])->schema([
                                Cep::make('postal_code')
                                    ->required()
                                    ->live(onBlur: true)
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
                                            'complement' => 'complemento',
                                            'number' => null,
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
                    ->placeholder('Sem banner'),
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
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->visible(fn ($record) => ! $record->trashed()),
                    Tables\Actions\DeleteAction::make()
                        ->successNotification(function ($record) {
                            return Notification::make()
                                ->warning()
                                ->title('Campeonato desativado')
                                ->body("<strong>{$record->name}</strong> está na lixeira.");
                        }),
                    Tables\Actions\RestoreAction::make()
                        ->successNotification(function ($record) {
                            return Notification::make()
                                ->success()
                                ->title('Campeonato restaurado')
                                ->body("<strong>{$record->name}</strong> está restaurado.");
                        })
                        ->visible(fn ($record) => $record->trashed()),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    // Tables\Actions\ForceDeleteBulkAction::make(),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Tabs')
                    ->tabs(
                        [
                            Tab::make('Descrição')
                                ->schema([
                                    Section::make()
                                        ->schema([
                                            \Filament\Infolists\Components\Grid::make(['default' => 1, 'sm' => 2, 'md' => 3, 'lg' => 5])->schema([

                                                // ImageEntry::make('banner_path')
                                                //     ->defaultImageUrl(fn($record) => $record->getFirstMediaUrl() ?? '')
                                                //     ->hiddenLabel()
                                                //     ->height(150)
                                                //     ->width(150)
                                                //     ->columnSpan(1),
                                                \Filament\Infolists\Components\Group::make(['default' => 1, 'md' => 1, 'lg' => 1])->schema([

                                                    TextEntry::make('name')->label('Nome'),
                                                    TextEntry::make('registration_fee')
                                                        ->label('Taxa de inscrição')
                                                        ->money(currency: 'BRL'),

                                                ]),
                                                \Filament\Infolists\Components\Group::make(['default' => 1, 'md' => 1, 'lg' => 1])->schema([
                                                    TextEntry::make('start_date')
                                                        ->label('Data de Início')
                                                        ->color(color: 'primary')
                                                        ->date('d/m/Y'),
                                                    TextEntry::make('end_date')
                                                        ->label('Data de Termino')
                                                        ->color(color: 'danger')
                                                        ->date('d/m/Y'),
                                                ]),
                                                \Filament\Infolists\Components\Group::make(['default' => 1, 'md' => 1, 'lg' => 1])->schema([
                                                    TextEntry::make('game_platform')
                                                        ->label('Plataforma'),
                                                    TextEntry::make('game')
                                                        ->label('Jogo'),
                                                ]),
                                                \Filament\Infolists\Components\Group::make(['default' => 1, 'md' => 1, 'lg' => 1])->schema([
                                                    TextEntry::make('championship_format')
                                                        ->label('Formato'),
                                                    TextEntry::make('max_players')
                                                        ->label('Máximo de jogadores'),
                                                ]),
                                                \Filament\Infolists\Components\Group::make(['default' => 1, 'md' => 1, 'lg' => 1])->schema([
                                                    TextEntry::make('wpp_group_link')
                                                        ->copyable()
                                                        ->label('Link do grupo de WhatsApp'),
                                                    TextEntry::make('status')
                                                        ->badge()
                                                        ->label('Status'),
                                                ]),
                                            ]),
                                        ]),
                                    Section::make()
                                        ->schema([
                                            \Filament\Infolists\Components\Grid::make(['default' => 1, 'lg' => 1])->schema([
                                                TextEntry::make('description')
                                                    ->label('Descrição')
                                                    ->html(),
                                                TextEntry::make('information')
                                                    ->label('Informação')
                                                    ->html(),
                                            ]),
                                        ]),
                                ]),

                            Tab::make('Endereço')
                                ->schema([
                                    Section::make()->schema([
                                        \Filament\Infolists\Components\Grid::make(['default' => 1, 'sm' => 2, 'md' => 3, 'lg' => 5])->schema([
                                            \Filament\Infolists\Components\Group::make()->schema([
                                                TextEntry::make('address.postal_code')
                                                    ->label('CEP'),
                                                TextEntry::make('address.state')->label('UF'),
                                                TextEntry::make('address.city')->label('Cidade'),
                                            ]),
                                            \Filament\Infolists\Components\Group::make()->schema([
                                                TextEntry::make('address.neighborhood')->label('Bairro'),
                                                TextEntry::make('address.street')->label('Rua'),
                                                TextEntry::make('address.number')->label('Número'),
                                            ]),
                                        ]),
                                    ]),
                                ]),
                        ]
                    )->columnSpanFull(),
            ]);
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

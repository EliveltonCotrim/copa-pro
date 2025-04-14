<?php

namespace App\Filament\Resources\ChampionshipResource\RelationManagers;

use App\Enum\{PaymentMethodEnum, PaymentStatusEnum, RegistrationPlayerStatusEnum};
use App\Models\{Payment, Player};
use Filament\Forms\Components\{Select, TextInput};
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletingScope};

class RegistrationPlayersRelationManager extends RelationManager
{
    protected static string $relationship = 'RegistrationPlayers';

    protected static ?string $title = 'Inscrições';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('championship_team_name')
                    ->label('Nome do Time do Campeonato')
                    ->required()
                    ->maxLength(255),
                Select::make('payment_method')
                    ->options(PaymentMethodEnum::class)
                    ->searchable()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->label('Metodo de pagamento'),
                Select::make('payment_status')
                    ->options(PaymentStatusEnum::class)
                    ->visible(fn (string $context): bool => $context === 'create')
                    ->searchable()
                    ->required()
                    ->label('Status do Pagamento'),
                Select::make('status')
                    ->options(RegistrationPlayerStatusEnum::class)
                    ->searchable()
                    ->required()
                    ->label('Status'),
                Select::make('player_id')
                    ->options(Player::with('user')->get()->pluck('user.name', 'id'))
                    ->unique('registration_players', 'player_id', ignoreRecord: true, modifyRuleUsing: function ($rule, $get, $livewire) {
                        return $rule->where('championship_id', $livewire->ownerRecord->id)->where('player_id', $get('player_id'))->withoutTrashed();
                    })
                    ->searchable()
                    ->required()
                    ->label('Jogador'),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('player.user.name')->searchable()->label('Nome'),
                Tables\Columns\TextColumn::make('player.user.email')->label('E-mail'),
                Tables\Columns\TextColumn::make('player.phone')->label('WhatsApp'),
                Tables\Columns\TextColumn::make('championship_team_name')->searchable()->label('Time do Campeonato'),
                Tables\Columns\TextColumn::make('status')->label('Status')
                    ->toggleable()->badge(),
                Tables\Columns\TextColumn::make('payments.status')->label('Status do Pagamento')
                    ->toggleable()->badge(),

            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->successRedirectUrl(function (Model $record, array $data) {
                        $record = $record->load('payments', 'championship');
                        Payment::create([
                            'value'                  => $record?->championship?->registration_fee ?? '00.00',
                            'billing_type'           => $data['payment_method'],
                            'registration_player_id' => $record->id,
                            'date_created'           => now()->format('Y-m-d'),
                            'status'                 => $data['payment_status'],
                        ]);
                    })
                    ->label('Cadastrar Jogador'),
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
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}

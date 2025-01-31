<?php

namespace App\Filament\Resources\ChampionshipResource\RelationManagers;

use App\Enum\PaymentStatusEnum;
use App\Enum\PlayerPlatformGameEnum;
use App\Enum\PlayerSexEnum;
use App\Enum\RegistrationPlayerStatusEnum;
use App\Models\Player;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

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
                Select::make('status')
                    ->options(RegistrationPlayerStatusEnum::class)
                    ->searchable()
                    ->required()
                    ->label('Status'),
                Select::make('payment_status')
                    ->options(PaymentStatusEnum::class)
                    ->searchable()
                    ->required()
                    ->label('Status do Pagamento'),
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
                Tables\Columns\TextColumn::make('phone')->label('WhatsApp'),
                Tables\Columns\TextColumn::make('championship_team_name')->searchable()->label('Time do Campeonato'),
                Tables\Columns\TextColumn::make('payment_status')->label('Status do Pagamento')
                    ->toggleable()->badge(),

            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Cadastrar Jogador'),
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

<?php

namespace App\Livewire\Championship;

use App\Models\Championship;
use Blade;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use View;

class RegistrationForm extends Component implements HasForms
{

    use InteractsWithForms;

    public Championship $championship;
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form)
    {
        return $form
            ->schema([
                Wizard::make()
                    ->columnSpanFull()
                    ->schema([
                        Wizard\Step::make('Inscrição')
                            ->description('Informe os dados para realizar a inscrição')
                            ->icon('heroicon-o-user')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nome')
                                    ->required(),
                                TextInput::make('email')
                                    ->label('E-mail')
                                    ->required(),
                                TextInput::make('championship_team_name')
                                    ->label('Nome do Time do Campeonato')
                                    ->required()
                                    ->maxLength(255),

                            ])->afterValidation(function () {
                                dd('sd');
                            })->actionFormModel($this->championship),
                        Wizard\Step::make('Pagamento')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([

                            ]),
                    ])->submitAction(new HtmlString(Blade::render(<<<BLADE
                        <x-filament::button
                            type="submit"
                            size="sm"
                        >
                            Finalizar inscrição
                        </x-filament::button>
                    BLADE)))
            ])->statePath('data');
    }

    public function save()
    {
        dd($this->form->getState());
    }

    public function render()
    {
        return view('livewire.championship.registration-form');
    }
}

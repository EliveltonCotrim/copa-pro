<?php

use App\Http\Middleware\CheckChampionshipStatus;
use App\Livewire\Championship\RegistrationForm;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('campeonato/{championship:slug}/inscricao', RegistrationForm::class)
    ->middleware(CheckChampionshipStatus::class)
    ->name('championship.register');

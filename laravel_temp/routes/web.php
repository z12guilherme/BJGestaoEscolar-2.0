<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\ProfessorController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:diretor'])->group(function () {
    Route::resource('schools', SchoolController::class);
    Route::resource('professores', ProfessorController::class);
});

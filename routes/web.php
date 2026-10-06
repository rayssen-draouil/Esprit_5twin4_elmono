<?php

use App\Http\Controllers\Back\BackController;
use App\Http\Controllers\FinancementController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/about', [FrontController::class, 'about'])->name('front.about');
Route::get('/services', [FrontController::class, 'services'])->name('front.services');
Route::get('/incidents', [FrontController::class, 'incidents'])->name('front.incidents.index');
Route::get('/incidents/create', [FrontController::class, 'createIncident'])->name('front.incidents.create');
Route::get('/incidents/{incident}', [FrontController::class, 'showIncident'])->name('front.incidents.show');
Route::get('/infrastructures', [FrontController::class, 'infrastructures'])->name('front.infrastructures.index');
Route::get('/infrastructures/{infrastructure}', [FrontController::class, 'showInfrastructure'])->name('front.infrastructures.show');
Route::get('/projects', [FrontController::class, 'projects'])->name('front.projects.index');
Route::get('/projects/{project}', [FrontController::class, 'showProject'])->name('front.projects.show');
Route::get('/funding', [FrontController::class, 'funding'])->name('front.funding');
Route::get('/news', [FrontController::class, 'news'])->name('front.news');
Route::get('/contact', [FrontController::class, 'contact'])->name('front.contact');

Route::prefix('back')->name('back.')->group(function () {
    Route::get('/', [BackController::class, 'dashboard'])->name('dashboard');
    Route::get('/incidents', [BackController::class, 'incidents'])->name('incidents');
    Route::get('/infrastructures', [BackController::class, 'infrastructures'])->name('infrastructures');
    Route::get('/projects', [BackController::class, 'projects'])->name('projects');
    Route::get('/funding', [BackController::class, 'funding'])->name('funding');
    Route::get('/users', [BackController::class, 'users'])->name('users');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [BackController::class, 'dashboard'])->name('dashboard');
    Route::get('/incidents', [BackController::class, 'incidents'])->name('incidents.index');
    Route::get('/infrastructures', [BackController::class, 'infrastructures'])->name('infrastructures.index');
    Route::get('/funding', [BackController::class, 'funding'])->name('funding.index');
    Route::get('/users', [BackController::class, 'users'])->name('users.index');
});

Route::prefix('admin')->group(function () {
    Route::resource('projects', ProjectController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->names('projects');

    Route::resource('financements', FinancementController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->names('financements');
});

<?php

use App\Http\Controllers\Back\BackController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinancementController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/dashboard/manager', [DashboardController::class, 'manager'])->middleware('role:manager,gestionnaire')->name('manager.dashboard');
    Route::get('/dashboard/citizen', [DashboardController::class, 'citizen'])->middleware('role:citizen,citoyen')->name('citizen.dashboard');
});

Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/about', [FrontController::class, 'about'])->name('front.about');
Route::get('/services', [FrontController::class, 'services'])->name('front.services');
Route::middleware('auth')->group(function () {
    Route::get('/incidents', [FrontController::class, 'incidents'])->name('front.incidents.index');
    Route::get('/incidents/create', [FrontController::class, 'createIncident'])->name('front.incidents.create');
    Route::post('/incidents', [FrontController::class, 'storeIncident'])->name('front.incidents.store');
    Route::get('/incidents/{incident}', [FrontController::class, 'showIncident'])->name('front.incidents.show');
});
Route::get('/infrastructures', [FrontController::class, 'infrastructures'])->name('front.infrastructures.index');
Route::get('/infrastructures/{infrastructure}', [FrontController::class, 'showInfrastructure'])->name('front.infrastructures.show');
Route::get('/projects', [FrontController::class, 'projects'])->name('front.projects.index');
Route::get('/projects/{project}', [FrontController::class, 'showProject'])->name('front.projects.show');
Route::get('/funding', [FrontController::class, 'funding'])->name('front.funding');
Route::get('/news', [FrontController::class, 'news'])->name('front.news');
Route::get('/contact', [FrontController::class, 'contact'])->name('front.contact');

Route::middleware(['auth', 'role:admin,manager,gestionnaire'])->prefix('back')->name('back.')->group(function () {
    Route::get('/', [BackController::class, 'dashboard'])->name('dashboard');
    Route::get('/incidents', [BackController::class, 'incidents'])->name('incidents');
    Route::post('/signalements/{signalement}/confirm', [BackController::class, 'confirmSignalement'])->name('signalements.confirm');
    Route::get('/infrastructures', [BackController::class, 'infrastructures'])->name('infrastructures');
    Route::get('/projects', [BackController::class, 'projects'])->name('projects');
    Route::get('/funding', [BackController::class, 'funding'])->name('funding');
    Route::get('/users', [BackController::class, 'users'])->name('users');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [BackController::class, 'dashboard'])->name('dashboard');
    Route::get('/incidents', [BackController::class, 'incidents'])->name('incidents.index');
    Route::get('/infrastructures', [BackController::class, 'infrastructures'])->name('infrastructures.index');
    Route::get('/funding', [BackController::class, 'funding'])->name('funding.index');
    Route::patch('/signalements/{signalement}', [BackController::class, 'updateSignalement'])->name('signalements.update');
    Route::delete('/signalements/{signalement}', [BackController::class, 'destroySignalement'])->name('signalements.destroy');
    Route::patch('/incidents/{incident}', [BackController::class, 'updateIncident'])->name('incidents.update');
    Route::delete('/incidents/{incident}', [BackController::class, 'destroyIncident'])->name('incidents.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('users', UserController::class)->names('admin.users');
});

Route::middleware(['auth', 'role:admin,manager,gestionnaire'])->prefix('admin')->group(function () {
    Route::resource('projects', ProjectController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->names('projects');

    Route::resource('financements', FinancementController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->names('financements');
});

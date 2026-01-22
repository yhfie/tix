<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Category CRUD
    Route::resource('categories', CategoryController::class);

    // Event CRUD
    Route::resource('events', EventController::class);

    // Ticket CRUD
    Route::resource('tickets', TicketController::class);
});

require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Movie\MovieController;
use App\Http\Controllers\Movie\TapeController;
use App\Http\Controllers\Movie\ActorController;
use App\Http\Controllers\Movie\CategoryController;
use App\Http\Controllers\Movie\UserController;
use App\Http\Controllers\Movie\RoleController;
use App\Http\Controllers\Movie\AuditLogController;
use App\Http\Controllers\Movie\DashboardController;
use App\Http\Controllers\Movie\ProfileController;
use App\Http\Controllers\Movie\RentalController;
use App\Http\Controllers\Movie\ReportController;

Route::get('/', fn() => view('landing'))->name('home')->middleware('guest');

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── MOVIES ACCESS CONTROL ─────────────────────────────────────────────
    Route::middleware(['permission:create movies'])->group(function () {
        Route::resource('movies', MovieController::class)->only(['create', 'store']);
    });
    Route::middleware(['permission:edit movies'])->group(function () {
        Route::resource('movies', MovieController::class)->only(['edit', 'update']);
    });
    Route::middleware(['permission:view movies'])->group(function () {
        Route::resource('movies', MovieController::class)->only(['index', 'show']);
    });
    Route::middleware(['permission:delete movies'])->group(function () {
        Route::resource('movies', MovieController::class)->only(['destroy']);
    });

    // ── TAPES ACCESS CONTROL ──────────────────────────────────────────────
    Route::middleware(['permission:create tapes'])->group(function () {
        Route::resource('tapes', TapeController::class)->only(['create', 'store']);
    });
    Route::middleware(['permission:edit tapes'])->group(function () {
        Route::resource('tapes', TapeController::class)->only(['edit', 'update']);
    });
    Route::middleware(['permission:view tapes'])->group(function () {
        Route::resource('tapes', TapeController::class)->only(['index', 'show']);
    });
    Route::middleware(['permission:delete tapes'])->group(function () {
        Route::resource('tapes', TapeController::class)->only(['destroy']);
    });

    // ── ACTORS ACCESS CONTROL ─────────────────────────────────────────────
    Route::middleware(['permission:create actors'])->group(function () {
        Route::resource('actors', ActorController::class)->only(['create', 'store']);
    });
    Route::middleware(['permission:edit actors'])->group(function () {
        Route::resource('actors', ActorController::class)->only(['edit', 'update']);
    });
    Route::middleware(['permission:view actors'])->group(function () {
        Route::resource('actors', ActorController::class)->only(['index', 'show']);
    });
    Route::middleware(['permission:delete actors'])->group(function () {
        Route::resource('actors', ActorController::class)->only(['destroy']);
    });

    // ── CATEGORIES ACCESS CONTROL ─────────────────────────────────────────
    Route::middleware(['permission:create categories'])->group(function () {
        Route::resource('categories', CategoryController::class)->only(['create', 'store']);
    });
    Route::middleware(['permission:edit categories'])->group(function () {
        Route::resource('categories', CategoryController::class)->only(['edit', 'update']);
    });
    Route::middleware(['permission:view categories'])->group(function () {
        Route::resource('categories', CategoryController::class)->only(['index', 'show']);
    });
    Route::middleware(['permission:delete categories'])->group(function () {
        Route::resource('categories', CategoryController::class)->only(['destroy']);
    });

    // ── ADMINISTRATIVE GATE (Strict Admin Only) ───────────────────────────
    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    // ── SECURITY COMPLIANCE ───────────────────────────────────────────────
    Route::middleware(['permission:view audit logs'])->group(function () {
        Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show']);
    });

    // ── RENTAL MANAGEMENT ─────────────────────────────────────────────────
    Route::get('/movies/{movie}/rent', [RentalController::class, 'quickRent'])->name('movies.rent');
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::get('/tapes/{tape}/rent', [RentalController::class, 'create'])->name('rentals.create');
    Route::post('/tapes/{tape}/rent', [RentalController::class, 'store'])->name('rentals.store');
    Route::patch('/rentals/{rental}/return', [RentalController::class, 'returnRental'])->name('rentals.return');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
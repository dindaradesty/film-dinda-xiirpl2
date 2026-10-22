<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    FilmController,
    CastController,
    GenreController,
    UserController,
    AuthController,
    DashboardController,
    KritikController,
};

/*
|--------------------------------------------------------------------------
| Autentikasi (Register, Login, Logout)
|--------------------------------------------------------------------------
| Form login/register mengirim data dengan method POST, jadi rutenya
| HARUS Route::post() (bukan Route::get()). Ini juga yang memperbaiki
| MethodNotAllowedHttpException ketika form di-submit.
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
*/
Route::get('/', [FilmController::class, 'movieHome'])->name('home');
Route::get('/movies', [FilmController::class, 'movies'])->name('movies');
Route::get('/movies/genre/{genre}', [FilmController::class, 'moviesByGenre'])->name('genre');
Route::get('/movies/{film}', [FilmController::class, 'show'])->name('movies.show');

// Kirim komentar/ulasan (wajib login).
Route::post('/movies/{film}/comments', [KritikController::class, 'store'])
    ->middleware('auth')
    ->name('movies.comments.store');

/*
|--------------------------------------------------------------------------
| Dashboard Admin (hanya role_id = 2 / admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:2'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('film', FilmController::class)->except(['show']);
    Route::resource('cast', CastController::class)->except(['show']);
    Route::resource('genre', GenreController::class)->except(['show']);
    Route::resource('user', UserController::class)->only(['index', 'destroy']);
});

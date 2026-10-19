<?php

namespace App\Http\Controllers;

use App\Models\Cast;
use App\Models\Film;
use App\Models\Genre;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Dashboard admin (hanya untuk role_id = 2).
     */
    public function index()
    {
        $stats = [
            'films' => Film::count(),
            'casts' => Cast::count(),
            'genres' => Genre::count(),
            'users' => User::count(),
        ];

        $latestFilms = Film::with('genre')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestFilms'));
    }
}

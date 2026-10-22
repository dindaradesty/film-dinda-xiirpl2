<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Kritik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KritikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Simpan komentar/ulasan baru untuk sebuah film.
     * user_id diambil otomatis dari user yang sedang login.
     */
    public function store(Request $request, Film $film)
    {
        $data = $request->validate([
            'comment' => ['required', 'string', 'min:3', 'max:2000'],
        ], [
            'comment.required' => 'Komentar/ulasan wajib diisi.',
            'comment.min' => 'Komentar/ulasan minimal 3 karakter.',
            'comment.max' => 'Komentar/ulasan maksimal 2000 karakter.',
        ]);

        $film->kritiks()->create([
            'user_id' => Auth::id(),
            'comment' => $data['comment'],
            // Kolom rating di tabel kritiks wajib diisi, form ini tidak
            // meminta rating sehingga default-nya 0.
            'rating' => 0,
        ]);

        return back()->withFragment('comments')
            ->with('success', 'Komentar/ulasan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kritik $kritik)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kritik $kritik)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kritik $kritik)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kritik $kritik)
    {
        //
    }
}

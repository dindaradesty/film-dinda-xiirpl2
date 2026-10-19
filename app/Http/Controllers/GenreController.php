<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Tampilkan daftar genre.
     */
    public function index()
    {
        $genres = Genre::withCount('films')->orderBy('name')->paginate(15);

        return view('admin.genres.index', compact('genres'));
    }

    /**
     * Form tambah genre.
     */
    public function create()
    {
        return view('admin.genres.create');
    }

    /**
     * Simpan genre baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45|unique:genres,name',
        ]);

        Genre::create($validated);

        return redirect()->route('admin.genre.index')->with('success', 'Data genre berhasil ditambahkan!');
    }

    /**
     * Form edit genre.
     */
    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', compact('genre'));
    }

    /**
     * Perbarui genre.
     */
    public function update(Request $request, Genre $genre)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45|unique:genres,name,' . $genre->id,
        ]);

        $genre->update($validated);

        return redirect()->route('admin.genre.index')->with('success', 'Data genre berhasil diperbarui!');
    }

    /**
     * Hapus genre. Ditolak jika masih dipakai oleh film
     * (kolom films.genre_id bersifat NOT NULL + foreign key).
     */
    public function destroy(Genre $genre)
    {
        if ($genre->films()->count() > 0) {
            return redirect()->route('admin.genre.index')
                ->with('error', 'Genre "' . $genre->name . '" tidak bisa dihapus karena masih dipakai oleh film.');
        }

        $genre->delete();

        return redirect()->route('admin.genre.index')->with('success', 'Data genre berhasil dihapus!');
    }
}

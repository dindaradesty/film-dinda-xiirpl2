<?php

namespace App\Http\Controllers;

use App\Models\Cast;
use Illuminate\Http\Request;

class CastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $casts = Cast::all();
        return view('admin.casts.index', compact('casts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.casts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'bio' => 'nullable|string',
        ]);

        Cast::create($validated);

        return redirect()->route('admin.cast.index')->with('success', 'Data cast berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cast $cast)
    {
        // Opsional jika ingin menampilkan detail cast
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cast $cast)
    {
        return view('admin.casts.edit', compact('cast'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cast $cast)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'bio' => 'nullable|string',
        ]);

        $cast->update($validated);

        return redirect()->route('admin.cast.index')->with('success', 'Data cast berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cast $cast)
    {
        $cast->delete();

        return redirect()->route('admin.cast.index')->with('success', 'Data cast berhasil dihapus!');
    }
}
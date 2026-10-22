<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Genre;
use App\Models\Cast;
use App\Models\Kritik;
use App\Models\Peran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FilmController extends Controller
{
    /* ------------------------------------------------------------------
     | Halaman Publik
     | ------------------------------------------------------------------ */

    public function movieHome()
    {
        $films = Film::select('id', 'title', 'poster', 'year')
            ->orderBy('created_at', 'asc')
            ->limit(9)->get();
        $filmFutured = Film::select('id', 'title', 'poster', 'year')
            ->orderByDesc('year')
            ->orderBy('created_at', 'asc')
            ->limit(6)->get();
        $filmRecentAdded = Film::select('id', 'title', 'poster', 'year')
            ->orderByDesc('created_at')
            ->limit(6)->get();
        $filmSlidey = Film::select('title', 'poster', 'sinopsis')
            ->orderByDesc('year')
            ->orderBy('created_at', 'asc')
            ->limit(6)->get();

        return view('components/home', compact('filmSlidey', 'films', 'filmFutured', 'filmRecentAdded'));
    }

    public function movies()
    {
        $genreFilm = null;
        $search = request('search');

        $films = Film::select('id', 'title', 'poster', 'year')
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->orderByDesc('year')
            ->orderBy('created_at', 'asc')
            ->paginate(18)
            ->withQueryString();

        return view('components/movies', compact('films', 'genreFilm'));
    }

    public function moviesByGenre($genre)
    {
        $genreFilm = Genre::find($genre);
        $films = Film::select('id', 'title', 'poster', 'year')
            ->where('genre_id', '=', $genre)
            ->orderByDesc('year')
            ->orderBy('created_at', 'asc')
            ->paginate(18);

        return view('components/movies', compact('films', 'genreFilm'));
    }

    public function show(Film $film)
    {
        $filmByGenre = Film::select('id', 'title', 'poster', 'year', 'sinopsis')
            ->where('genre_id', '=', $film->genre_id)
            ->orderBy('created_at', 'asc')
            ->limit(7)
            ->get();
        $filmByRelease = Film::select('id', 'title', 'poster', 'year')
            ->where('year', '=', Carbon::now()->format('Y'))
            ->orderBy('created_at', 'asc')
            ->limit(9)
            ->get();
        $comments = Kritik::with('user')
            ->select('comment', 'user_id')
            ->where('film_id', '=', $film->id)
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();
        $perans = Peran::with('cast')->where('film_id', '=', $film->id)->get();

        return view('components.movie-show', compact('film', 'filmByGenre', 'filmByRelease', 'comments', 'perans'));
    }

    /* ------------------------------------------------------------------
     | Dashboard Admin (CRUD Film)
     | ------------------------------------------------------------------ */

    public function index()
    {
        $films = Film::with(['genre', 'pemeran'])->paginate(10);

        return view('admin.films.index', compact('films'));
    }

    public function create()
    {
        $genres = Genre::all();
        $casts = Cast::all();

        return view('admin.films.create', compact('genres', 'casts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sinopsis' => 'nullable|string',
            'year' => 'required|integer',
            'genre_id' => 'required|exists:genres,id',
            'poster' => 'nullable|string',
            'casts' => 'nullable|array',
            'casts.*' => 'exists:casts,id',
        ]);

        $data = $request->only(['title', 'sinopsis', 'year', 'genre_id', 'poster']);
        $data['sinopsis'] = $data['sinopsis'] ?? '';

        $film = Film::create($data);

        if ($request->has('casts')) {
            $film->pemeran()->attach($request->casts);
        }

        return redirect()->route('admin.film.index')->with('success', 'Data film berhasil ditambahkan!');
    }

    public function edit(Film $film)
    {
        $genres = Genre::all();
        $casts = Cast::all();
        $film->load('pemeran');

        return view('admin.films.edit', compact('film', 'genres', 'casts'));
    }

    public function update(Request $request, Film $film)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sinopsis' => 'nullable|string',
            'year' => 'required|integer',
            'genre_id' => 'required|exists:genres,id',
            'poster' => 'nullable|string',
            'casts' => 'nullable|array',
            'casts.*' => 'exists:casts,id',
        ]);

        $data = $request->only(['title', 'sinopsis', 'year', 'genre_id', 'poster']);
        $data['sinopsis'] = $data['sinopsis'] ?? '';

        $film->update($data);

        if ($request->has('casts')) {
            $film->pemeran()->sync($request->casts);
        } else {
            $film->pemeran()->detach();
        }

        return redirect()->route('admin.film.index')->with('success', 'Data film berhasil diperbarui!');
    }

    public function destroy(Film $film)
    {
        $film->pemeran()->detach();
        $film->delete();

        return redirect()->route('admin.film.index')->with('success', 'Data film berhasil dihapus!');
    }
}

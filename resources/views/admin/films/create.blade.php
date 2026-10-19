@extends('templates.index')

@section('content')
<div class="container admin-page">
    @include('admin.partials.nav')

    <h3 class="admin-title">Tambah Data Film</h3>

    <div class="row">
        <div class="col-md-9">
            <div class="admin-panel">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul style="margin: 0; padding-left: 18px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.film.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="title">Judul Film</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="year">Tahun Rilis</label>
                        <input type="number" class="form-control" id="year" name="year" value="{{ old('year') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="genre_id">Genre</label>
                        <select class="form-control" id="genre_id" name="genre_id" required>
                            <option value="" disabled selected>-- Pilih Genre --</option>
                            @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}" {{ old('genre_id') == $genre->id ? 'selected' : '' }}>
                                    {{ $genre->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="poster">Path Poster / Nama File Gambar</label>
                        <input type="text" class="form-control" id="poster" name="poster" value="{{ old('poster') }}" placeholder="contoh: storage/images/c10.jpg">
                    </div>

                    <div class="form-group">
                        <label for="sinopsis">Sinopsis</label>
                        <textarea class="form-control" id="sinopsis" name="sinopsis" rows="3">{{ old('sinopsis') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Pilih Pemeran (Cast)</label>
                        <div style="max-height: 180px; overflow-y: auto; border: 1px solid #eee; padding: 10px;">
                            @forelse ($casts as $cast)
                                <label style="display: block; margin-bottom: 5px; font-weight: 400;">
                                    <input type="checkbox" name="casts[]" value="{{ $cast->id }}"
                                        {{ is_array(old('casts')) && in_array($cast->id, old('casts')) ? 'checked' : '' }}>
                                    {{ $cast->nama }} ({{ $cast->umur }} Thn)
                                </label>
                            @empty
                                <span style="color: #999;">Belum ada data cast. Silakan tambah data cast terlebih dahulu.</span>
                            @endforelse
                        </div>
                    </div>

                    <div style="margin-top: 20px;">
                        <a href="{{ route('admin.film.index') }}" class="btn btn-dark2">Kembali</a>
                        <button type="submit" class="btn btn-orange">Simpan Film</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

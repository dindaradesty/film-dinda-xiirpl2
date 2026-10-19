@extends('templates.index')

@section('content')
<div class="container admin-page">
    @include('admin.partials.nav')

    <h3 class="admin-title">Tambah Data Cast</h3>

    <div class="row">
        <div class="col-md-8">
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

                <form action="{{ route('admin.cast.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="nama">Nama Cast</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="umur">Umur</label>
                        <input type="number" class="form-control" id="umur" name="umur" value="{{ old('umur') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea class="form-control" id="bio" name="bio" rows="4">{{ old('bio') }}</textarea>
                    </div>

                    <div style="margin-top: 20px;">
                        <a href="{{ route('admin.cast.index') }}" class="btn btn-dark2">Kembali</a>
                        <button type="submit" class="btn btn-orange">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

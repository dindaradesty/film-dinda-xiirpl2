@extends('templates.index')

@section('content')
<div class="container admin-page">
    @include('admin.partials.nav')

    <h3 class="admin-title">Tambah Data Genre</h3>

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

                <form action="{{ route('admin.genre.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">Nama Genre</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" maxlength="45" placeholder="contoh: Action" required>
                    </div>

                    <div style="margin-top: 20px;">
                        <a href="{{ route('admin.genre.index') }}" class="btn btn-dark2">Kembali</a>
                        <button type="submit" class="btn btn-orange">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

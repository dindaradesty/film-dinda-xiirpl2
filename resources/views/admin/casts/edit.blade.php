@extends('templates.index')

@section('content')
<div class="container admin-page">
    @include('admin.partials.nav')

    <h3 class="admin-title">Edit Data Cast</h3>

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

                <form action="{{ route('admin.cast.update', $cast->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Nama Cast</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $cast->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="age">Umur</label>
                        <input type="number" class="form-control" id="age" name="age" value="{{ old('age', $cast->age) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea class="form-control" id="bio" name="bio" rows="4">{{ old('bio', $cast->bio) }}</textarea>
                    </div>

                    <div style="margin-top: 20px;">
                        <a href="{{ route('admin.cast.index') }}" class="btn btn-dark2">Kembali</a>
                        <button type="submit" class="btn btn-orange">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

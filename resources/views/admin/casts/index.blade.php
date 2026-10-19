@extends('templates.index')

@section('content')
<div class="container admin-page">
    @include('admin.partials.nav')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h3 class="admin-title">Kelola Data Cast</h3>

    <p style="margin-bottom: 20px;">
        <a href="{{ route('admin.cast.create') }}" class="btn btn-orange">+ Tambah Cast</a>
    </p>

    <div class="admin-panel">
        <div class="table-responsive">
            <table class="table table-striped admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Nama Cast</th>
                        <th style="width: 120px;">Umur</th>
                        <th>Bio</th>
                        <th style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($casts as $index => $cast)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $cast->name }}</strong></td>
                            <td>{{ $cast->age }} Tahun</td>
                            <td>{{ Str::limit($cast->bio, 60) }}</td>
                            <td>
                                <a href="{{ route('admin.cast.edit', $cast->id) }}" class="btn btn-sm btn-orange">Edit</a>
                                <form action="{{ route('admin.cast.destroy', $cast->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus cast ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-dark2">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align: center; padding: 20px;">Belum ada data cast.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('templates.index')

@section('content')
<div class="container admin-page">
    @include('admin.partials.nav')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h3 class="admin-title">Kelola Data Film</h3>

    <p style="margin-bottom: 20px;">
        <a href="{{ route('admin.film.create') }}" class="btn btn-orange">+ Tambah Film</a>
    </p>

    <div class="admin-panel">
        <div class="table-responsive">
            <table class="table table-striped admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 90px;">Poster</th>
                        <th>Judul</th>
                        <th>Tahun</th>
                        <th>Genre</th>
                        <th>Cast / Pemeran</th>
                        <th style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($films as $index => $film)
                        <tr>
                            <td>{{ $films->firstItem() + $index }}</td>
                            <td>
                                @if ($film->poster)
                                    <img src="{{ asset($film->poster) }}" alt="{{ $film->title }}" style="width: 55px; height: 75px; object-fit: cover;">
                                @else
                                    <span class="label label-default">No Image</span>
                                @endif
                            </td>
                            <td><strong>{{ $film->title }}</strong></td>
                            <td>{{ $film->year }}</td>
                            <td><span class="label label-warning">{{ $film->genre->name ?? 'Tanpa Genre' }}</span></td>
                            <td>
                                @forelse ($film->pemeran as $cast)
                                    <span class="label label-default" style="display: inline-block; margin-bottom: 3px;">{{ $cast->name }}</span>
                                @empty
                                    <span style="color: #999;">Belum ada cast</span>
                                @endforelse
                            </td>
                            <td>
                                <a href="{{ route('admin.film.edit', $film->id) }}" class="btn btn-sm btn-orange">Edit</a>
                                <form action="{{ route('admin.film.destroy', $film->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus film ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-dark2">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align: center; padding: 20px;">Belum ada data film.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="text-align: center;">{{ $films->links() }}</div>
    </div>
</div>
@endsection

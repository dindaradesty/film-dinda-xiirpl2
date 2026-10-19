@extends('templates.index')

@section('content')
<div class="container admin-page">
    @include('admin.partials.nav')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h3 class="admin-title">Kelola Data Genre</h3>

    <p style="margin-bottom: 20px;">
        <a href="{{ route('admin.genre.create') }}" class="btn btn-orange">+ Tambah Genre</a>
    </p>

    <div class="admin-panel">
        <div class="table-responsive">
            <table class="table table-striped admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Nama Genre</th>
                        <th style="width: 150px;">Jumlah Film</th>
                        <th style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($genres as $index => $genre)
                        <tr>
                            <td>{{ $genres->firstItem() + $index }}</td>
                            <td><strong>{{ $genre->name }}</strong></td>
                            <td><span class="label label-warning">{{ $genre->films_count }}</span></td>
                            <td>
                                <a href="{{ route('admin.genre.edit', $genre->id) }}" class="btn btn-sm btn-orange">Edit</a>
                                <form action="{{ route('admin.genre.destroy', $genre->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus genre ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-dark2">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align: center; padding: 20px;">Belum ada data genre.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="text-align: center;">{{ $genres->links() }}</div>
    </div>
</div>
@endsection

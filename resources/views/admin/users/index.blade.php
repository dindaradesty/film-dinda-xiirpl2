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

    <h3 class="admin-title">Kelola Data User</h3>

    <div class="admin-panel">
        <div class="table-responsive">
            <table class="table table-striped admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th style="width: 130px;">Role</th>
                        <th style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if ($user->id === auth()->id())
                                    <span class="label label-default">Anda</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ((int) $user->role_id === 2)
                                    <span class="label label-warning">Admin</span>
                                @else
                                    <span class="label label-default">{{ $user->role->name ?? 'user' }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($user->id === auth()->id())
                                    <span style="color: #999;">&mdash;</span>
                                @else
                                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-dark2">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align: center; padding: 20px;">Belum ada data user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="text-align: center;">{{ $users->links() }}</div>
    </div>
</div>
@endsection

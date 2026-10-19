@extends('templates.index')

@section('content')
<div class="container admin-page">
    @include('admin.partials.nav')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h3 class="admin-title">Dashboard Admin</h3>

    <p style="margin-bottom: 25px;">
        Selamat datang, <strong>{{ auth()->user()->name }}</strong>! Anda login sebagai
        <span class="label label-warning">Admin</span>.
    </p>

    @php
        $cards = [
            ['label' => 'Total Film', 'value' => $stats['films'], 'link' => route('admin.film.index')],
            ['label' => 'Total Cast', 'value' => $stats['casts'], 'link' => route('admin.cast.index')],
            ['label' => 'Total Genre', 'value' => $stats['genres'], 'link' => route('movies')],
            ['label' => 'Total User', 'value' => $stats['users'], 'link' => null],
        ];
    @endphp

    <div class="row">
        @foreach ($cards as $card)
            <div class="col-md-3 col-sm-6" style="margin-bottom: 20px;">
                <div class="admin-panel" style="text-align: center;">
                    <h2 style="color: #FF8D1B; margin: 0; font-size: 2.4em; font-weight: bold;">{{ $card['value'] }}</h2>
                    <p style="text-transform: uppercase; letter-spacing: 1px; margin-top: 10px;">
                        @if ($card['link'])
                            <a href="{{ $card['link'] }}" style="color: #212121;">{{ $card['label'] }}</a>
                        @else
                            {{ $card['label'] }}
                        @endif
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="admin-panel" style="margin-top: 10px;">
        <h3>Film Terbaru</h3>
        <div class="table-responsive">
            <table class="table table-striped admin-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Genre</th>
                        <th>Tahun</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestFilms as $film)
                        <tr>
                            <td>{{ $film->title }}</td>
                            <td>{{ $film->genre->name ?? '-' }}</td>
                            <td>{{ $film->year }}</td>
                            <td><a href="{{ route('admin.film.edit', $film->id) }}" class="btn btn-sm btn-orange">Edit</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align: center; padding: 15px;">Belum ada data film.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

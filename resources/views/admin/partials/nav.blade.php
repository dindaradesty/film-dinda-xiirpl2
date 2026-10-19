@push('style')
<style>
    .admin-page { padding: 30px 0 50px; }
    .admin-nav { margin: 0 0 25px; }
    .admin-nav a { display: inline-block; padding: 8px 18px; margin: 0 6px 8px 0; background: #212121; color: #fff; text-transform: uppercase; font-size: 13px; font-weight: bold; border-radius: 2px; }
    .admin-nav a:hover, .admin-nav a.active { background: #FF8D1B; color: #fff; text-decoration: none; }
    .admin-title { color: #212121; border-bottom: 2px solid #FF8D1B; padding-bottom: 8px; margin: 0 0 20px; font-size: 1.6em; text-transform: uppercase; }
    .admin-panel { background: #fff; border: 1px solid #ececec; border-top: 3px solid #FF8D1B; padding: 20px; box-shadow: 0 0 4px rgba(0, 0, 0, .08); }
    .admin-panel h3 { margin-top: 0; color: #212121; font-size: 1.2em; }
    .admin-table th { background: #212121; color: #fff !important; }
    .btn-orange { background: #FF8D1B; color: #fff; border: 0; }
    .btn-orange:hover { background: #212121; color: #fff; }
    .btn-dark2 { background: #212121; color: #fff; border: 0; }
    .btn-dark2:hover { background: #FF8D1B; color: #fff; }
</style>
@endpush

<div class="admin-nav">
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
    <a href="{{ route('admin.film.index') }}" class="{{ request()->routeIs('admin.film.*') ? 'active' : '' }}">Kelola Film</a>
    <a href="{{ route('admin.cast.index') }}" class="{{ request()->routeIs('admin.cast.*') ? 'active' : '' }}">Kelola Cast</a>
    <a href="{{ route('admin.genre.index') }}" class="{{ request()->routeIs('admin.genre.*') ? 'active' : '' }}">Kelola Genre</a>
    <a href="{{ route('admin.user.index') }}" class="{{ request()->routeIs('admin.user.*') ? 'active' : '' }}">Kelola User</a>
    <a href="{{ route('home') }}">Lihat Situs &raquo;</a>
</div>

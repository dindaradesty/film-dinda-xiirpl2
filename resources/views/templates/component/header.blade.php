<div class="header">
  <div class="container">
    <div class="w3layouts_logo">
      <a href="{{ route('home') }}"><h1>One<span>Movies</span></h1></a>
    </div>
    <div class="w3_search">
      <form action="{{ route('movies') }}" method="GET">
        <input type="text" name="search" placeholder="Search" value="{{ request('search') }}">
        <input type="submit" value="Go">
      </form>
    </div>
    <div class="w3l_sign_in_register">
      <ul>
        @auth
          @if (auth()->user()->isAdmin())
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          @endif
          <li>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
              @csrf
              <button type="submit" style="background: #FF8D1B; color: #fff; border: 0; text-transform: uppercase; font-weight: bold; padding: 6px 30px; cursor: pointer;">Logout</button>
            </form>
          </li>
        @else
          <li><a href="#" data-toggle="modal" data-target="#myModal">Login</a></li>
        @endauth
      </ul>
    </div>
    <div class="clearfix"> </div>
  </div>
</div>

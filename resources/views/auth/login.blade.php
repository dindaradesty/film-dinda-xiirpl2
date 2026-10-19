@extends('templates.index')

@section('content')
<div class="container" style="padding:45px 0;">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="w3_login_module">
                <div class="module form-module">
                    <div class="toggle"><a href="{{ route('register') }}" title="Belum punya akun? Daftar" style="color:#fff;"><i class="fa fa-user-plus"></i></a></div>

                    <div class="form">
                        <h3>Login to your Account</h3>

                        @if (session('success'))
                            <div class="alert alert-success" style="font-size:14px;">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger" style="font-size:14px;">
                                <ul style="margin:0;padding-left:18px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('login.attempt') }}" method="POST">
                            @csrf

                            <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required autofocus>
                            <input type="password" name="password" placeholder="Password" required>

                            <label style="display:block;text-align:left;font-weight:400;margin:-8px 0 15px;color:#666;">
                                <input type="checkbox" name="remember" value="1" style="width:auto;display:inline-block;margin-right:6px;"> Remember me
                            </label>

                            <input type="submit" value="Login">
                        </form>
                    </div>

                    <div class="cta">
                        <a href="{{ route('register') }}">Belum punya akun? <u>Register di sini</u></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

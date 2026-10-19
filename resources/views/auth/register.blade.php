@extends('templates.index')

@section('content')
<div class="container" style="padding:45px 0;">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="w3_login_module">
                <div class="module form-module">
                    <div class="toggle"><a href="{{ route('login') }}" title="Sudah punya akun? Login" style="color:#fff;"><i class="fa fa-sign-in"></i></a></div>

                    <div class="form">
                        <h3>Create an Account</h3>

                        @if ($errors->any())
                            <div class="alert alert-danger" style="font-size:14px;">
                                <ul style="margin:0;padding-left:18px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('register.store') }}" method="POST">
                            @csrf

                            <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus>
                            <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
                            <input type="password" name="password" placeholder="Password (min. 6 karakter)" required>
                            <input type="password" name="password_confirmation" placeholder="Ulangi Password" required>

                            <input type="submit" value="Register">
                        </form>
                    </div>

                    <div class="cta">
                        <a href="{{ route('login') }}">Sudah punya akun? <u>Login di sini</u></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

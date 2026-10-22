@php
  // Tentukan form mana yang harus tampil (dipakai saat ada error validasi,
  // supaya modal tetap terbuka di form yang tepat).
  $showRegister = old('name') !== null || old('password_confirmation') !== null;
@endphp

<style>
  .form-module .auth-tabs { display: flex; border-bottom: 1px solid #ececec; }
  .form-module .auth-tabs .auth-tab {
    flex: 1; background: #f4f4f4; border: 0; padding: 13px 10px;
    font-weight: bold; text-transform: uppercase; letter-spacing: 1px;
    color: #888; cursor: pointer; transition: .3s; font-size: 13px;
  }
  .form-module .auth-tabs .auth-tab:hover { color: #FF8D1B; }
  .form-module .auth-tabs .auth-tab.active { background: #FF8D1B; color: #fff; }
  .form-module .auth-error {
    background: #fdecea; border-left: 4px solid #c0392b; color: #c0392b;
    text-align: left; font-size: 13px; padding: 8px 12px; margin-bottom: 15px;
  }
  .form-module .auth-error ul { margin: 0; padding-left: 18px; }
  .form-module .auth-switch { color: #FF8D1B; font-size: 13px; }
  .form-module .cta.auth-cta { padding: 12px 10px; text-align: center; }
</style>

<!-- Popup Auth (LOGIN & REGISTER dalam satu modal) -->
<div class="modal video-modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Account
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <section>
        <div class="modal-body">
          <div class="w3_login_module">
            <div class="module form-module">

              {{-- Tab switcher LOGIN / REGISTER --}}
              <div class="auth-tabs">
                <button type="button" class="auth-tab" data-auth-tab="login">Login</button>
                <button type="button" class="auth-tab" data-auth-tab="register">Register</button>
              </div>

              {{-- ================= FORM LOGIN ================= --}}
              <div class="form auth-form" id="authLoginForm" data-auth-panel="login">
                <h3>Login to your account</h3>

                @if ($errors->any() && ! $showRegister)
                  <div class="auth-error">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <form action="{{ route('login.attempt') }}" method="POST">
                  @csrf
                  <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
                  <input type="password" name="password" placeholder="Password" required>
                  <label style="display:block;text-align:left;font-weight:400;margin:-8px 0 12px;color:#666;font-size:13px;">
                    <input type="checkbox" name="remember" value="1" style="width:auto;display:inline-block;margin-right:6px;"> Remember me
                  </label>
                  <input type="submit" value="Login">
                </form>

                <div class="cta auth-cta">
                  <a href="#" class="auth-switch" data-auth-tab="register">Belum punya akun? Register di sini</a>
                </div>
              </div>

              {{-- ================= FORM REGISTER ================= --}}
              <div class="form auth-form" id="authRegisterForm" data-auth-panel="register">
                <h3>Create an account</h3>

                @if ($errors->any() && $showRegister)
                  <div class="auth-error">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <form action="{{ route('register.store') }}" method="POST">
                  @csrf
                  <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                  <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
                  <input type="password" name="password" placeholder="Password (min. 6 karakter)" required>
                  <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
                  <input type="submit" value="Register">
                </form>

                <div class="cta auth-cta">
                  <a href="#" class="auth-switch" data-auth-tab="login">Sudah punya akun? Login di sini</a>
                </div>
              </div>

            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>

<script>
  (function () {
    function activateAuthForm(form) {
      if (form === 'register') {
        $('#authLoginForm').hide();
        $('#authRegisterForm').show();
      } else {
        $('#authRegisterForm').hide();
        $('#authLoginForm').show();
      }
      $('.auth-tab').removeClass('active');
      $('.auth-tab[data-auth-tab="' + form + '"]').addClass('active');
    }

    // Switcher: dipakai oleh tab atas maupun link di bawah form.
    $(document).on('click', '[data-auth-tab]', function (e) {
      e.preventDefault();
      activateAuthForm($(this).data('auth-tab'));
    });

    $(function () {
      activateAuthForm('{{ $showRegister ? 'register' : 'login' }}');

      @if (($errors->has('email') || $errors->has('password') || $errors->has('name') || $errors->has('password_confirmation')) && ! request()->routeIs('login') && ! request()->routeIs('register'))
        // Jika ada error validasi dari modal login/register, buka lagi modalnya.
        $('#myModal').modal('show');
      @endif
    });
  })();
</script>

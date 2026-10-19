<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route($this->homeRouteFor(Auth::user()));
        }

        return view('auth.login');
    }

    /**
     * Proses login (POST).
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()
                ->intended(route($this->homeRouteFor(Auth::user())))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()
            ->withErrors(['email' => 'Email atau password yang Anda masukkan salah.'])
            ->onlyInput('email');
    }

    /**
     * Tampilkan halaman register.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route($this->homeRouteFor(Auth::user()));
        }

        return view('auth.register');
    }

    /**
     * Proses register (POST). Data disimpan ke tabel users dengan
     * role_id = 1 (user biasa).
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Kolom profile_id di tabel users bersifat wajib (NOT NULL),
        // jadi kita buatkan profile default untuk user baru.
        $profile = Profile::create([
            'umur' => 0,
            'bio' => '-',
            'alamat' => '-',
        ]);

        // role_id 1 = "user" (lihat RoleSeeder), role_id 2 = "admin".
        $userRoleId = Role::where('name', 'user')->value('id') ?? 1;

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $userRoleId,
            'profile_id' => $profile->id,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('home')
            ->with('success', 'Registrasi berhasil. Selamat datang, ' . $user->name . '!');
    }

    /**
     * Proses logout (POST).
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Tentukan halaman tujuan setelah login/register.
     */
    private function homeRouteFor(?User $user): string
    {
        return $user && $user->isAdmin() ? 'admin.dashboard' : 'home';
    }
}

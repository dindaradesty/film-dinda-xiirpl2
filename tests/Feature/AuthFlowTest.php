<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    public function test_public_pages_load(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/movies')->assertStatus(200);
        $this->get('/login')->assertStatus(200)->assertSee('Login to your Account');
        $this->get('/register')->assertStatus(200)->assertSee('Create an Account');
    }

    public function test_guest_is_redirected_from_admin(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_register_creates_user_with_role_and_profile(): void
    {
        $email = 'uji' . uniqid() . '@example.com';

        $this->post('/register', [
            'name' => 'User Uji',
            'email' => $email,
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect('/');

        $this->assertAuthenticated();

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertSame(1, (int) $user->role_id);
        $this->assertNotNull($user->profile_id);
        $this->assertTrue(Hash::check('rahasia123', $user->password));

        $user->delete();
        Profile::where('id', $user->profile_id)->delete();
    }

    public function test_seeded_admin_can_login_and_open_dashboard(): void
    {
        if (! User::where('email', 'admin@example.com')->exists()) {
            $this->markTestSkipped('User admin belum di-seed (jalankan: php artisan db:seed).');
        }

        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => '12345678',
        ])->assertRedirect('/admin');

        $this->assertAuthenticated();
        $this->get('/admin')->assertStatus(200)->assertSee('Dashboard Admin');
        $this->get('/admin/film')->assertStatus(200);
        $this->get('/admin/cast')->assertStatus(200);
    }

    public function test_normal_user_cannot_open_admin_dashboard(): void
    {
        $user = User::where('role_id', 1)->first();
        if (! $user) {
            $this->markTestSkipped('Tidak ada user role 1.');
        }

        $this->actingAs($user)->get('/admin')->assertStatus(403);
    }

    public function test_search_filters_movies_page(): void
    {
        $this->get('/movies?search=a')->assertStatus(200);
        $this->get('/movies')->assertStatus(200)->assertSee('List Of Movies');
    }

    public function test_admin_film_crud_pages_render(): void
    {
        $admin = User::where('role_id', 2)->first();
        if (! $admin) {
            $this->markTestSkipped('Tidak ada admin.');
        }

        \App\Models\Film::first();

        $this->actingAs($admin)->get('/admin/film/create')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/film')->assertStatus(200)->assertSee('Kelola Data Film');
        $this->actingAs($admin)->get('/admin/cast')->assertStatus(200)->assertSee('Kelola Data Cast');

        $film = \App\Models\Film::first();
        $this->actingAs($admin)->get('/admin/film/' . $film->id . '/edit')->assertStatus(200);
    }

    public function test_auth_modal_contains_login_and_register_forms(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('id="myModal"', false)
            ->assertSee('id="authLoginForm"', false)
            ->assertSee('id="authRegisterForm"', false)
            ->assertSee('action="' . route('login.attempt') . '"', false)
            ->assertSee('action="' . route('register.store') . '"', false)
            ->assertSee('data-auth-tab="login"', false)
            ->assertSee('data-auth-tab="register"', false)
            ->assertSee('name="password_confirmation"', false)
            ->assertSee('name="_token"', false)
            ->assertDontSee('action="#"', false);
    }

    public function test_register_validation_error_reopens_modal_with_message(): void
    {
        $this->from('/')
            ->post('/register', [
                'name' => 'Tanpa Konfirmasi',
                'email' => 'salah' . uniqid() . '@example.com',
                'password' => 'rahasia123',
                'password_confirmation' => 'beda123',
            ])
            ->assertRedirect('/')
            ->assertSessionHasErrors('password');
    }

    public function test_logout(): void
    {
        $admin = User::where('role_id', 2)->first();
        if (! $admin) {
            $this->markTestSkipped('Tidak ada admin.');
        }

        $this->actingAs($admin)->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }
}

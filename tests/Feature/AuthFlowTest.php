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

    public function test_film_form_shows_cast_name(): void
    {
        $admin = User::where('role_id', 2)->first();
        $cast = \App\Models\Cast::first();

        if (! $admin || ! $cast) {
            $this->markTestSkipped('Butuh user admin dan data cast.');
        }

        $this->actingAs($admin)
            ->get('/admin/film/create')
            ->assertStatus(200)
            ->assertSee($cast->name);

        $film = \App\Models\Film::with('pemeran')->first();
        if ($film) {
            $this->actingAs($admin)
                ->get('/admin/film/' . $film->id . '/edit')
                ->assertStatus(200);
        }
    }

    public function test_admin_can_create_cast(): void
    {
        $admin = User::where('role_id', 2)->first();
        if (! $admin) {
            $this->markTestSkipped('Butuh user admin.');
        }

        $name = 'Uji Cast ' . uniqid();

        $this->actingAs($admin)->post('/admin/cast', [
            'name' => $name,
            'age' => 30,
            'bio' => 'bio uji',
        ])->assertRedirect('/admin/cast');

        $cast = \App\Models\Cast::where('name', $name)->first();
        $this->assertNotNull($cast);
        $this->assertSame(30, (int) $cast->age);

        $cast->delete();
    }

    public function test_admin_nav_has_genre_and_user_menus(): void
    {
        $admin = User::where('role_id', 2)->first();
        if (! $admin) {
            $this->markTestSkipped('Butuh user admin.');
        }

        $this->actingAs($admin)->get('/admin')
            ->assertStatus(200)
            ->assertSee('Kelola Genre')
            ->assertSee('Kelola User');
    }

    public function test_admin_genre_pages_render_and_crud(): void
    {
        $admin = User::where('role_id', 2)->first();
        if (! $admin) {
            $this->markTestSkipped('Butuh user admin.');
        }

        $this->actingAs($admin)->get('/admin/genre')->assertStatus(200)->assertSee('Kelola Data Genre');
        $this->actingAs($admin)->get('/admin/genre/create')->assertStatus(200);

        $name = 'Genre Uji ' . uniqid();
        $this->actingAs($admin)->post('/admin/genre', ['name' => $name])->assertRedirect('/admin/genre');

        $genre = \App\Models\Genre::where('name', $name)->first();
        $this->assertNotNull($genre);

        $this->actingAs($admin)->get('/admin/genre/' . $genre->id . '/edit')->assertStatus(200);
        $this->actingAs($admin)->put('/admin/genre/' . $genre->id, ['name' => $name . ' X'])
            ->assertRedirect('/admin/genre');

        $this->assertSame($name . ' X', $genre->fresh()->name);

        $genre->delete();
    }

    public function test_genre_that_is_used_by_film_cannot_be_deleted(): void
    {
        $admin = User::where('role_id', 2)->first();
        $film = \App\Models\Film::first();
        if (! $admin || ! $film) {
            $this->markTestSkipped('Butuh admin dan film.');
        }

        $genre = \App\Models\Genre::find($film->genre_id);
        $this->assertNotNull($genre);

        $this->actingAs($admin)->delete('/admin/genre/' . $genre->id)
            ->assertRedirect('/admin/genre')
            ->assertSessionHas('error');

        $this->assertDatabaseHas('genres', ['id' => $genre->id]);
    }

    public function test_admin_user_list_and_delete(): void
    {
        $admin = User::where('role_id', 2)->first();
        if (! $admin) {
            $this->markTestSkipped('Butuh user admin.');
        }

        $this->actingAs($admin)->get('/admin/user')->assertStatus(200)->assertSee('Kelola Data User');

        // Admin tidak boleh menghapus akunnya sendiri.
        $this->actingAs($admin)->delete('/admin/user/' . $admin->id)
            ->assertRedirect('/admin/user')
            ->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);

        // Admin bisa menghapus user biasa.
        $profile = \App\Models\Profile::create(['umur' => 0, 'bio' => '-', 'alamat' => '-']);
        $user = User::create([
            'name' => 'User Hapus',
            'email' => 'hapus' . uniqid() . '@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('secret'),
            'role_id' => 1,
            'profile_id' => $profile->id,
        ]);

        $this->actingAs($admin)->delete('/admin/user/' . $user->id)->assertRedirect('/admin/user');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('profiles', ['id' => $profile->id]);
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

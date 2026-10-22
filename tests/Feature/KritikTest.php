<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\Kritik;
use App\Models\User;
use Tests\TestCase;

class KritikTest extends TestCase
{
    public function test_guest_cannot_post_comment(): void
    {
        $film = Film::first();

        if (! $film) {
            $this->markTestSkipped('Butuh data film.');
        }

        $this->post('/movies/' . $film->id . '/comments', [
            'comment' => 'Komentar dari tamu',
        ])->assertRedirect('/login');

        $this->assertGuest();
    }

    public function test_guest_sees_login_notice_instead_of_comment_form(): void
    {
        $film = Film::first();

        if (! $film) {
            $this->markTestSkipped('Butuh data film.');
        }

        $this->get('/movies/' . $film->id)
            ->assertStatus(200)
            ->assertSee('Silakan Login terlebih dahulu untuk memberikan komentar/ulasan.')
            ->assertSee('data-target="#myModal"', false)
            ->assertDontSee('action="' . route('movies.comments.store', $film->id) . '"', false);
    }

    public function test_authenticated_user_can_post_comment_and_see_it(): void
    {
        $film = Film::first();
        $user = User::where('role_id', 1)->first();

        if (! $film || ! $user) {
            $this->markTestSkipped('Butuh data film dan user biasa.');
        }

        $comment = 'Ulasan uji ' . uniqid();

        $this->actingAs($user)
            ->from('/movies/' . $film->id)
            ->post('/movies/' . $film->id . '/comments', ['comment' => $comment])
            ->assertRedirect('/movies/' . $film->id . '#comments')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('kritiks', [
            'film_id' => $film->id,
            'user_id' => $user->id,
            'comment' => $comment,
        ]);

        $this->actingAs($user)
            ->get('/movies/' . $film->id)
            ->assertStatus(200)
            ->assertSee($comment)
            ->assertSee($user->name)
            ->assertSee('action="' . route('movies.comments.store', $film->id) . '"', false);

        Kritik::where('comment', $comment)->delete();
    }

    public function test_comment_requires_content(): void
    {
        $film = Film::first();
        $user = User::where('role_id', 1)->first();

        if (! $film || ! $user) {
            $this->markTestSkipped('Butuh data film dan user biasa.');
        }

        $this->actingAs($user)
            ->from('/movies/' . $film->id)
            ->post('/movies/' . $film->id . '/comments', ['comment' => ''])
            ->assertRedirect('/movies/' . $film->id)
            ->assertSessionHasErrors('comment');
    }
}

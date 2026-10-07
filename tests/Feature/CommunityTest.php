<?php

namespace Tests\Feature;

use App\Models\CommunityThread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_chat(): void
    {
        $this->get(route('community.index'))->assertRedirect(route('chat.index', ['redirect' => '/community']));
    }

    public function test_user_can_create_join_and_post(): void
    {
        $user = User::factory()->create();
        $session = ['chat_user_id' => $user->id];

        $this->withSession($session)->post(route('community.threads.store'), ['title' => 'Bei za mahindi', 'description' => 'Dodoma'])->assertRedirect();
        $thread = CommunityThread::where('title', 'Bei za mahindi')->firstOrFail();
        $this->assertTrue($thread->members()->where('user_id', $user->id)->exists());

        $other = User::factory()->create();
        $this->withSession(['chat_user_id' => $other->id])->post(route('community.join', $thread->slug))->assertRedirect(route('community.show', $thread));
        $this->withSession(['chat_user_id' => $other->id])->post(route('community.posts.store', $thread->slug), ['content' => 'Shilingi 800 kwa kilo Kongwa'])->assertRedirect();

        $this->assertDatabaseHas('community_posts', ['content' => 'Shilingi 800 kwa kilo Kongwa', 'is_approved' => true]);
        $this->withSession($session)->get(route('community.show', $thread->slug))->assertOk()->assertSee('Shilingi 800');
    }

    public function test_abusive_posts_are_rejected(): void
    {
        $user = User::factory()->create();
        $thread = CommunityThread::create(['title' => 'T', 'slug' => 't', 'is_private' => false]);
        $thread->members()->attach($user->id);

        $this->withSession(['chat_user_id' => $user->id])->postJson(route('community.posts.store', $thread->slug), ['content' => 'wewe mjinga sana'])->assertStatus(422);
        $this->assertSame(1, $user->fresh()->abuse_count);
    }
}

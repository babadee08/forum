<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function unauthenticated_users_may_not_add_replies()
    {
        $this->expectException(AuthenticationException::class);

        $this->post('threads/1/replies', []);
    }
    
    /**
     * @test
     */
    public function an_authenticated_user_can_participate_in_forum_threads()
    {
        $this->be($user = factory(User::class)->create());

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make();

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())->assertSee($reply->body);
    }
}

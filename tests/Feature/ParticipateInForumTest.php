<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumtest extends TestCase
{
  use DatabaseMigrations;

  /** @test */
  public function unauthenticated_user_may_not_add_replies()
  {
    $this->expectException('Illuminate\Auth\AuthenticationException');

    $this->post('/threads/channels/1/replies', []);
  }

  /** @test */
  public function an_thenticated_user_may_participate_in_forum_threads()
  {
    $this->be(create('App\User'));

    $reply = make('App\Reply');

    $thread = create('App\Thread');

    $this->post($thread->path() . '/replies', $reply->toArray());

    $this->get($thread->path())
      ->assertSee($reply->body);
  }

  /** @test */
  public function a_reply_requires_a_body()
  {
    $this->withExceptionHandling()->signIn();

    $thread = create('App\Thread');
    $reply = make('App\Reply', ['body' => null]);

    $this->post($thread->path() . '/replies',  $reply->toArray())
      ->assertSessionHasErrors('body');
  }
}

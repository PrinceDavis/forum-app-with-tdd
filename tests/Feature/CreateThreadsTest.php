<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
  use DatabaseMigrations;

  /** @test */
  public function guest_cannot_see_the_create_thread_page()
  {
    $this->withExceptionHandling()
      ->get('/threads/create')
      ->assertRedirect('/login');
  }


  /** @test */
  public function guest_may_not_create_threads()
  {
    $this->expectException('Illuminate\Auth\AuthenticationException');

    $thread = make('App\Thread');
    $this->post('/threads', $thread->toArray());

  }


  /** @test */
  public function an_authenticated_user_can_create_forum_threads()
  {
    //Given that we have a signed in user
    //When we hit the endpoint to create a new thread
    //Then when we visit the thread page.
    //We should see the new thread
    $this->signIn();

    $thread = make('App\Thread');

    $response = $this->post('/threads', $thread->toArray());
    $this->get($response->headers->get('Location'))
      ->AssertSee($thread->title);
  }

  /** @test */
  public function a_thread_requires_a_title()
  {
    $this->publishThread(['title' => null])
      ->assertSessionHasErrors('title');
  }

  /** @test */
  public function a_thread_requires_a_body()
  {
    $this->publishThread(['body' => null])
      ->assertSessionHasErrors('body');
  }

  /** @test */
  public function a_thread_requires_a_valid_channel()
  {
    $this->publishThread(['channel_id' => null])
      ->assertSessionHasErrors('channel_id');

      $this->publishThread(['channel_id' => 999])
      ->assertSessionHasErrors('channel_id');
  }

  public function publishThread($overrides = [])
  {
    $this->withExceptionHandling()->signIn();
    $thread = make('App\Thread', $overrides);

    return $this->post('/threads', $thread->toArray());
  }


}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadTest extends TestCase
{

  use DatabaseMigrations;

  public function setUp()
  {
    parent::setUp();
    $this->thread = create('App\Thread');
  }
  /** @test */
  public function a_user_can_browse_threads()
  {
    $response = $this->get('/threads')->assertSee($this->thread->title);
  }

  /** @test */
  public function a_user_can_read_a_single_thread()
  {
    $response = $this->get($this->thread->path())->assertSee($this->thread->title);
  }

  /** @test */
  public function a_user_can_read_replies_associated_with_a_thread()
  {
    $reply = create('App\Reply', ['thread_id' => $this->thread->id]);
    $this->get($this->thread->path())->assertSee($reply->body);
  }

  /** @test */
  public function a_user_can_filter_threads_according_to_a_channel()
  {
    $channel = create('App\Channel');
    $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
    $threadNotInChannel = create('App\Thread');

    $this->get('/threads/' . $channel->slug)
      ->assertSee($threadInChannel->title)
      ->assertDontSee($threadNotInChannel->title);
  }

  /** @test */
  public function a_user_can_filter_threads_by_username()
  {
    $this->signIn(create('App\User', ['name' => 'JohnDoe']));

    $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
    $threadNotByJohn = create('App\Thread');

    $this->get('/threads?by=JohnDoe')
      ->assertSee($threadByJohn->title)
      ->assertDontSee($threadNotByJohn->title);
  }

  /** @test */
  public function a_user_can_filter_threads_by_popularity()
  {
    //given we have three threads
    //with 2 replies, 3, replies and 0 replies respectively
    //When i filter threads by popularity
    //Then they should be return from most replies to least
    
    $threadWith2Replies = create('App\Thread');
    create('App\Reply', ['thread_id' => $threadWith2Replies->id], 2);

    $threadWith3Replies = create('App\Thread');
    create('App\Reply', ['thread_id' => $threadWith3Replies->id], 3);

    $threadWith0Replies = $this->thread;

    $response = $this->getJson('/threads?popular=1')->json();
    $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
  } 
}

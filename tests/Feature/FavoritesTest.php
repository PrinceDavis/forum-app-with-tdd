<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
  use DatabaseMigrations;

  /** @test */
  public function guest_cannot_favorite_anything()
  {

    $this->withExceptionHandling()
      ->post('/replies/1/favorites')
      ->assertRedirect('/login');

  }
  /** @test */
  public function an_authenticated_user_can_favorite_any_reply()
  {
    // If i post to a "favorite" endpoint
    // it should be recorded on the database
    $reply = create('App\Reply');

    $this->signIn()
      ->post('/replies/' . $reply->id . '/favorites');
    $this->assertCount(1, $reply->favorites); 
  }

  /** @test */
  public function an_authenticated_user_may_only_favority_a_reply_once()
  {
    $reply = create('App\Reply');

    $this->signIn();
    try {
      $this->post('/replies/' . $reply->id . '/favorites');
      $this->post('/replies/' . $reply->id . '/favorites');
    } catch (\Exception $e) {
      $this->fail('Did not expect to insert the same record set twice.');
    }
    $this->assertCount(1, $reply->favorites); 
  }
}

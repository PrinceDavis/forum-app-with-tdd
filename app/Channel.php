<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
  /**
   * Get the route key name for laravel
   * @return String
   */
  public function getRouteKeyName(){
    return 'slug';
  }

  /**
   * A channel consist of threads
   * @return \Illuminate\Database\Eloquent\Relationship\HasMany 
   */
  public function threads()
  {
    return $this->hasMany(Thread::class);
  }
}

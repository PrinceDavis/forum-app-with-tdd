<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use Notifiable;

  /**
 * Get the route key name for laravel
 * @return String
 */
  public function getRouteKeyName(){
    return 'name';
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * Threads created by the current user
   * @return \Illuminate\Database\Elloquent\Relationship\HasMany [description]
   */
  public function threads()
  {
    return $this->hasMany(Thread::class)
      ->latest();
  }
}

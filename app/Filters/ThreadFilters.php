<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filters
{

  protected $filters = ['by', 'popular'];

  /**
   * filter the query by a given username
   * @param  string $username
   * @return mixed
   */
  protected function by($username)
  {
    $user = User::whereName($username)->firstOrFail();
    return $this->builder->where('user_id', $user->id);
  }

  /**
   * Filter the query according to most popular thread
   * @return $this
   */
  protected function popular()
  { 
    return $this->builder->orderBy('replies_count', 'desc'); 
  }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{

  protected $guarded = [];

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope('replyCount', function ($builder) {
      $builder->withCount('replies');
    });
  }

  public function path()
  {
    return "/threads/{$this->channel->slug}/{$this->id}";
  }
  public function replies()
  {
    return $this->hasMany(Reply::class);
  }

  /**
   * A Thread belongs to a creator
   * @return \Illuminate\Database\Eloquent\Relationship\BelongsTo
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function channel()
  {
    return $this->belongsTo(Channel::class);
  }

  public function addReply($reply)
  {
    $this->replies()->create($reply);
  }


  public function scopeFilter($query, $filters)
  {
    return $filters->apply($query);
  }

}

<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread;
use App\User;
use Illuminate\Http\Request;

class ThreadController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth')->except(['index', 'show']);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Channel $channel, ThreadFilters $filters)
  {

    $threads = $this->getThreads($channel, $filters);
    if (request()->wantsJson()) {
      return $threads;
    }
    return view('threads.index', compact('threads'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('threads.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'title' => 'required',
      'body' => 'required',
      'channel_id' => 'required|exists:channels,id'
    ]);

    $thread = Thread::create([
      'user_id' => auth()->id(),
      'channel_id' => request('channel_id'),
      'title' => request('title'),
      'body' => request('body')
    ]);

    return redirect($thread->path());
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Thread  $thread
   * @return \Illuminate\Http\Response
   */
  public function show($channel_id, Thread $thread)
  {
    return view('threads.show', [
        'thread' => $thread,
        'replies' => $thread->replies()->paginate(20)
      ]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Thread  $thread
   * @return \Illuminate\Http\Response
   */
  public function edit(Thread $thread)
  {
      //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Thread  $thread
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Thread $thread)
  {
      //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Thread  $thread
   * @return \Illuminate\Http\Response
   */
  public function destroy(Thread $thread)
  {
      //
  }

  public function getThreads($channel, $filters)
  {
    $threads = Thread::filter($filters)->latest();
    if ($channel->exists) {
      $threads->where('channel_id', $channel->id);
    }
    return $threads = $threads->get();
  }
}

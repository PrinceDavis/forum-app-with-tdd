<?php

namespace Tests;

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
  use CreatesApplication;


  public function setUp()
  {
    parent::setUp();
    $this->disableExceptionHandling();
  }
  public function signIn($user = null)
  {
    $user = $user?: create('App\User');
    $this->actingAs($user);
    return $this;
  }

  protected function disableExceptionHandling()
  {
    $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);

    $this->app->instance(ExceptionHandler::class, new class extends Handler {
        public function __construct() {}

        public function report(\Exception $e)
        {
          // no-op
        }
        public function render($request, \Exception $e)
        {
          throw $e;
        }
    });
  }

  public function withExceptionHandling()
  {
    $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);
    return $this;
  }
}



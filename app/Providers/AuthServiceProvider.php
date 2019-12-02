<?php

namespace App\Providers;

use App\Services\AuthService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

/**
 * Service provider for the authentication service
 */
class AuthServiceProvider extends ServiceProvider
{
  /**
   * Boot the authentication services for the application.
   *
   * @return void
   */
  public function boot()
  {
    //
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->bind('App\Services\AuthService', function ($app) {
      $request = $this->app->make(Request::class);
      return new AuthService($request->auth_code, env('SPOTIFY_APP_REDIRECT_URI'), new Client(), $request->refresh_token);
    });
  }
}

<?php

namespace App\Providers;

use App\Services\ProfileService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ProfileServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot()
  {
    //
  }

  /**
   * Register the application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->bind('App\Services\ProfileService', function ($app) {
      $request = $this->app->make(Request::class);
      return new ProfileService($request->bearerToken(), env('SPOTIFY_PROFILE_URL'), new Client());
    });
  }
}

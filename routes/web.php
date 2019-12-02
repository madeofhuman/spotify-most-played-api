<?php

  /*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It is a breeze. Simply tell Lumen the URIs it should respond to
  | and give it the Closure to call when that URI is requested.
  |
  */

  $router->get('/', function () use ($router) {
    return response()->json([
      'name' => 'Spotify Most Played API',
      'version' => '1.0',
      'author' => '@madeofhuman',
    ]);
  });

  $router->group(['prefix' => 'api_v1'], function () use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
      $router->post('login', 'AuthController@login');
      $router->post('refresh', 'AuthController@refresh');
    });
    $router->group(['prefix' => 'me'], function () use ($router) {
      $router->get('/', 'ProfileController@index');
      $router->get('top-listens', 'ProfileController@top_listens');
    });
  });

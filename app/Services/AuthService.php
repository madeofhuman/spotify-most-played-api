<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Handles the network calls to authenticate a user
 * with the Spotify auth service
 */
class AuthService
{
  private $url;
  private $http;
  private $auth_code;
  private $redirect_url;
  private $refresh_token;
  private $client_id;
  private $client_secret;

  public function __construct($auth_code, $redirect_url, Client $client, $refresh_token)
  {
    $this->url = env('SPOTIFY_AUTH_URL');
    $this->http = $client;
    $this->auth_code = $auth_code;
    $this->redirect_url = $redirect_url;
    $this->refresh_token = $refresh_token;
    $this->client_id = env('SPOTIFY_APP_CLIENT_ID');
    $this->client_secret = env('SPOTIFY_APP_CLIENT_SECRET');
  }

  /**
   * Called by AuthController to begin the authentication
   * process.
   */
  public function get_access_token()
  {
    return $this->_get_access_token();
  }

  /**
   * Called by AuthController to begin the process of retrieving
   * the access token using the refresh token.
   */
  public function refresh_token()
  {
    return $this->_refresh_token();
  }

  /**
   * Makes an API call to Spotify's auth service
   * to retrieve the user's access token
   *
   * @return array
   */
  private function _get_access_token(): array
  {
    $payload = [
      'grant_type' => 'authorization_code',
      'code' => $this->auth_code,
      'redirect_uri' => $this->redirect_url,
      'client_id' => $this->client_id,
      'client_secret' => $this->client_secret,
    ];
    return $this->fetch('POST', $this->url, $payload);
  }

  /**
   * Makes an API call to Spotify's auth service
   * to retrieve the user's access token using the
   * refresh token.
   *
   * @return array
   */
  private function _refresh_token(): array
  {
    $payload = [
      'grant_type' => 'refresh_token',
      'refresh_token' => $this->refresh_token,
    ];
    $encoded_client_id_secret = base64_encode(env('SPOTIFY_APP_CLIENT_ID').':'.env('SPOTIFY_APP_CLIENT_SECRET'));
    $headers = [
      'Authorization' => "Basic $encoded_client_id_secret"
    ];
    return $this->fetch('POST', $this->url, $payload, $headers);
  }

  /**
   * @param array $payload
   * @param string $url
   * @param string $method
   * @param array $headers
   * @return array
   */
  private function fetch(
    string $method, string $url,
    array $payload = [], array $headers = []): array
  {
    try {
      $res = $this->http->request($method, $url, [
        'form_params' => $payload,
        'headers' => $headers
      ]);
      if ($res->getStatusCode() == 200) {
        return json_decode($res->getBody(), true);
      }
    } catch (GuzzleException $e) {
      Log::info($e);
      abort($e->getCode(), $e->getMessage());
    }
  }
}

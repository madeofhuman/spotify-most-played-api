<?php


  namespace App\Services;


  use GuzzleHttp\Client;
  use GuzzleHttp\Exception\GuzzleException;
  use Illuminate\Support\Facades\Log;

  class ProfileService
  {
    private $access_token;
    private $http;
    private $url;

    public function __construct(string $access_token, string $url, Client $client)
    {
      $this->access_token = $access_token;
      $this->http = $client;
      $this->url = $url;
    }

    public function get_profile()
    {
      return $this->_get_profile();
    }

    public function get_top_listens()
    {
      $artist_listens = $this->_get_top_artist_listens();
      $track_listens = $this->_get_top_track_listens();
      return [
        'artists' => $artist_listens,
        'tracks' => $track_listens
      ];
    }

    private function _get_profile()
    {
      return $this->fetch($this->url);
    }

    private function _get_top_artist_listens()
    {
      // $long_term = $this->fetch($this->url.'/top/artists?time_range=long_term&limit=20');
      $medium_term = $this->fetch($this->url.'/top/artists?time_range=medium_term&limit=20');
      // $short_term = $this->fetch($this->url.'/top/artists?time_range=short_term&limit=20');
      return [
        'long_term' => $long_term,
        'medium_term' => $medium_term,
        'short_term' => $short_term,
      ];
    }

    private function _get_top_track_listens()
    {
      // $long_term = $this->fetch($this->url.'/top/tracks?time_range=long_term&limit=50');
      $medium_term = $this->fetch($this->url.'/top/tracks?time_range=medium_term&limit=50');
      // $short_term = $this->fetch($this->url.'/top/tracks?time_range=short_term&limit=50');
      return [
        // 'long_term' => $long_term,
        'medium_term' => $medium_term,
        // 'short_term' => $short_term,
      ];
    }

    /**
     * @param string $url
     * @return array
     */
    private function fetch(string $url): array
    {
      try {
        $headers = [
          'Authorization' => "Bearer $this->access_token",
        ];
        $res = $this->http->request('GET', $url, [
          'headers' => $headers
        ]);
        if ($res->getStatusCode() == 200) {
          return json_decode($res->getBody(), true);
        }
      } catch (GuzzleException $e) {
        Log::info($e->getMessage());
        abort($e->getCode(), $e->getMessage());
      }
    }
  }

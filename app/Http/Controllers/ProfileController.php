<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use App\Services\ResponseService;

class ProfileController extends Controller
{
  /**
   * @var ProfileService
   */
  private $profile_service;
  /**
   * @var ResponseService
   */
  private $response_service;

  public function __construct(ProfileService $profile_service, ResponseService $response_service)
    {
      $this->profile_service = $profile_service;
      $this->response_service = $response_service;
    }

    public function index()
    {
      $response = $this->profile_service->get_profile();
      return $this->response_service->response($response, 200);
    }

    public function top_listens()
    {
      $response = $this->profile_service->get_top_listens();
      return $this->response_service->response($response, 200);
    }
}

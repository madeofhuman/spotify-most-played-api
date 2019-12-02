<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\ResponseService;
use Illuminate\Http\Request;

class AuthController extends Controller
{

  /**
   * @var AuthService
   */
  private $auth_service;
  /**
   * @var ResponseService
   */
  private $response_service;

  /**
   * Create a new controller instance.
   *
   * @param AuthService $auth_service
   * @param ResponseService $response_service
   */
  public function __construct(AuthService $auth_service, ResponseService $response_service)
  {
    $this->auth_service = $auth_service;
    $this->response_service = $response_service;
  }

  public function login()
  {
    $response = $this->auth_service->get_access_token();
    return $this->response_service->response($response, 200);
  }

  public function refresh(Request $request)
  {
    $response = $this->auth_service->refresh_token();
    return $this->response_service->response($response, 200);
  }
}

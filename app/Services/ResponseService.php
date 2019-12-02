<?php

  namespace App\Services;

  use Illuminate\Http\JsonResponse;

  class ResponseService
  {

    /**
     * @param $response_data
     * @param $status
     * @return JsonResponse
     */
    public function response($response_data, $status)
    {
      switch (substr($status, 0, 1)) {
        case 2:
          return $this->_generate_success_response($response_data, $status);
        default:
          return $this->_generate_failure_response($response_data, $status);
      }
    }


    /**
     * @param $response_data
     * @param $status
     * @return JsonResponse
     */
    private function _generate_success_response($response_data, $status)
    {
      return response()->json([
        'ok' => true,
        'data' => $response_data,
        'error' => null,
      ], $status);
    }

    /**
     * @param $response_data
     * @param $status
     * @return JsonResponse
     */
    private function _generate_failure_response($response_data, $status)
    {
      return response()->json([
        'ok' => false,
        'data' => null,
        'error' => $response_data,
      ], $status);
    }
  }

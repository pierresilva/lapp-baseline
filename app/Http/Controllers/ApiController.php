<?php

namespace App\Http\Controllers;

class ApiController extends Controller
{

    /**
     * Return a response error
     *
     * @param string $message
     * @param array $errors
     * @param integer $code
     * @param integer $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError(
        string $message = 'Error',
        array $errors = [],
        int $code = 400,
        int $status = 1
    ) : \Illuminate\Http\JsonResponse {
        $body = [
            'status' => $status,
            'message' => $message,
            'response' => [
                'errors' => $errors,
            ],
        ];

        return response()->json($body, $code);
    }

    /**
     * Return a success response
     *
     * @param string $message
     * @param array $data
     * @param integer $code
     * @param integer $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess(
        string $message = 'OK',
        array $data = [],
        int $code = 200,
        int $status = 0
    ) : \Illuminate\Http\JsonResponse {
        $body = [
            'status' => $status,
            'message' => $message,
            'response' => [
                'data' => $data,
                'meta' => $this->getMeta($data),
            ],
        ];

        return response()->json($body, $code);
    }

    /**
     * Return a pagination meta array
     *
     * @param array $data
     * @return array
     */
    private function getMeta(array $data = []) : array
    {
        if (null !== @$data['data']) {
            unset($data['data']);
        }

        return $data;
    }
}

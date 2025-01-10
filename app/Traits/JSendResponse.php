<?php

namespace App\Traits;

trait JSendResponse
{
    public function jsend_success($data, $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], $code);
    }

    public function jsend_fail($data, $code = 400)
    {
        return response()->json([
            'status' => 'fail',
            'data' => $data
        ], $code);
    }

    public function jsend_error($message, $data = null, $code = 500)
    {
        $response = collect();

        $response->put('status', 'error');
        $response->put('message', $message);

        if ($data) {
            $response->put('data', $data);
        }

        return response()->json($response->toArray(), $code);
    }
}

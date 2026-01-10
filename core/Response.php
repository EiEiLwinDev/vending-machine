<?php

class Response
{
    public static function success($data = null, $message = "OK", $code = 200)
    {
        http_response_code($code);
        echo json_encode([
            'status' => 'success',
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }

    public static function error($message = "Error", $code = 400, $data = null)
    {
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
}
?>
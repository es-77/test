<?php

namespace App\Utils;

class ResponseUtil
{
    public static function getResponseArray($data, $response_code = 101, $message = '', $errors = '')
    {
        if (!$data || (is_countable($data) && sizeof($data) == 0)) {
            $data = null;
        }

        return [
            'response' => $response_code,
            'message' => $message,
            'validation_errors' => $errors,
            'data' => $data,
        ];
    }
}

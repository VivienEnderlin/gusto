<?php
class Response {
    public static function success($data) {
        header('Content-Type: application/json');
        echo json_encode(["success" => true, "data" => $data]);
        exit;
    }

    public static function error($message, $code = 400) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(["success" => false, "message" => $message]);
        exit;
    }
}

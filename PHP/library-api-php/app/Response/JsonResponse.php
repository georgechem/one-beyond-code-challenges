<?php

namespace App\Response;

class JsonResponse
{
    public static function send(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

}

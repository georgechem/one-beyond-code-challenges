<?php
namespace App\Controllers;

use App\Response\JsonResponse;


class FineController {

    // GET /fine
    public function index(): void {

        JsonResponse::send(['message' => 'OK']);
    }
}

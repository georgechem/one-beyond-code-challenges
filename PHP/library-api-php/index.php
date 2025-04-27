<?php

use App\Controllers\BookController;
use App\Controllers\FineController;
use App\Controllers\LoanController;
use App\Controllers\ReservationController;
use App\Data\Data;

require __DIR__ . '/vendor/autoload.php';


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

/**
 * We do not have a framework here, but that state management here can be
 * compared to middleware as we're doing some action between request was sent and response was returned
 * Simple state management as REST API is stateless.
 * We have a global state due to singleton usage
 *
 * Wew could pass it to every controller constructor. However, that pollutes it a little bit.
 * It is easier to call Data::get() when needed.
 * In this app, every controller will use it, but for example if we want to make only
 * request to the third party from any endpoint that states there has no usage
 *
 */
$state = Data::get();

function eventOnTerminate(Data $state) : void{
    $state->save();
}

if ($uri === '/books' && $method === 'GET') {
    $controller = new BookController();
    $controller->index();
} elseif ($uri === '/loans' && $method === 'GET') {
    $controller = new LoanController();
    $controller->index();

} elseif ($uri === '/loans/return' && $method === 'POST') {
    $controller = new LoanController();
    $controller->returnBook();

} elseif ($uri === '/reservations' && $method === 'POST') {
    $controller = new ReservationController();
    $controller->reserve();

} elseif ($uri === '/reservations' && $method === 'GET') {
    $controller = new ReservationController();
    $controller->status();
} elseif ($uri === '/fines' && $method === 'GET') {
    $controller = new FineController();
    $controller->index();
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found']);
    die;
}

eventOnTerminate($state);

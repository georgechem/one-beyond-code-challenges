<?php
namespace App\Controllers;


use App\Data\Data;
use App\Response\JsonResponse;


/**
 * Controllers normally return and handle the output of content internally,
 * but for simplicity we are echoing the output here.
 */
class BookController {
    public function index(): void  {

        $books = Data::get()->getBooks();

        JsonResponse::send($books);
    }
}

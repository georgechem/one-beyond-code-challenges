<?php
namespace App\Controllers;

use App\Repository\BookRepository;
use App\Response\JsonResponse;

/**
 * Controllers normally return and handle the output of content internally,
 * but for simplicity we are echoing the output here.
 */
class BookController {
    public function index(): void  {

        $bookRepository = new BookRepository();
        $books = $bookRepository->getBooks();

        JsonResponse::send($books);
    }
}

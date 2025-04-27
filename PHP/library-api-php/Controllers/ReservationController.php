<?php
namespace App\Controllers;

use App\Repository\BookStockRepository;
use App\Response\JsonResponse;
use App\Services\BookStockService;
use App\Validator\Constraints\BookIdBorrowerIdConstraint;
use App\Validator\Constraints\BookIdConstraint;
use App\Validator\Validator;

class ReservationController {
    // POST /reservations

    public function reserve(): void {

        $result = Validator::validate(new BookIdBorrowerIdConstraint());

        if(!empty($errors = $result->getErrors())){
            JsonResponse::send(['message' => $errors]);
            die;
        }

        ['bookId' => $bookId, 'borrowerId' => $borrowerId] = $result->getValues();

        $bookStockService = new BookStockService();
        $result = $bookStockService->reserveTheBook($bookId, $borrowerId);

        $message = sprintf('Book with id %d was reserver for borrower with id %d', $bookId, $borrowerId);
        if(!$result){
            $message = sprintf('Book with id %d could not be reserver for borrower', $bookId);
        }

        JsonResponse::send(['message' => $message]);
    }

    // GET /reservations
    public function status(): void {
        $result = Validator::validate(new BookIdConstraint());

        if(!empty($errors = $result->getErrors())){
            JsonResponse::send(['message' => $errors]);
            die;
        }

        ['bookId' => $bookId] = $result->getValues();

        $bookStockRepository = new BookStockRepository();

        $availability = $bookStockRepository->getAvailabilityForBook($bookId);

        JsonResponse::send(['availability' => $availability]);
    }
}

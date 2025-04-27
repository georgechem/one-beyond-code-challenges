<?php
namespace App\Controllers;

use App\Repository\LoanRepository;
use App\Response\JsonResponse;
use App\Services\BookStockService;
use App\Validator\Constraints\BookIdBorrowerIdConstraint;
use App\Validator\Validator;


class LoanController {
    // GET /loans
    public function index(): void {
        // TODO: Implement logic to list active loans with borrower and book details.
        /**
         * In a full scale application, we would inject the repository,
         * not instantiate it here.
         */
        $loanRepository = new LoanRepository();
        $activeLoans = $loanRepository->getActive();

        JsonResponse::send($activeLoans);
    }

    // POST /loans/return
    public function returnBook(): void {
        // TODO: Implement logic to process the return of a book and calculate fines if overdue.

        $result = Validator::validate(new BookIdBorrowerIdConstraint());

        if(!empty($errors = $result->getErrors())){
            JsonResponse::send(['message' => $errors]);
            die;
        }

        ['bookId' => $bookId, 'borrowerId' => $borrowerId] = $result->getValues();

        $bookStockService = new BookStockService();
        $status = $bookStockService->returnBook($bookId, $borrowerId);

        $message = sprintf('Returned Book with id %d', $bookId);
        if(!$status){
            $message = sprintf('Book with id %d was not returned', $bookId);
        }

        JsonResponse::send(['message' => $message]);;
    }
}

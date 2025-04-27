<?php
namespace App\Repository;

use App\Models\BookStock;

class LoanRepository extends AbstractRepository
{
    public function getActive(): array
    {
        $activeLoans = array_filter(
            $this->storage->getBookStocks(),
            fn(BookStock $bs) => $bs->isOnLoan
        );

        if(empty($activeLoans)){
            return [];
        }

        $bookRepository = new BookRepository();
        $borrowerRepository = new BorrowerRepository();

        foreach ($activeLoans as $bookStock) {
            $book = $bookRepository->findById($bookStock->bookId, $bookRepository->getBooks());
            $borrower = $borrowerRepository->findById($bookStock->borrowerId, $borrowerRepository->getBorrowers());
            $bookStock->book = $book;
            $bookStock->borrower = $borrower;
        }

        return $activeLoans;
    }
}

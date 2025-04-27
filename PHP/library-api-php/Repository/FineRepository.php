<?php
namespace App\Repository;

use App\Models\Book;
use App\Models\BookStock;
use App\Models\Fine;

class FineRepository extends AbstractRepository
{
    public function getActiveLoans(): array
    {
        $stocks = array_map(
            fn(array $row) => new BookStock($row['id'], $row['bookId'], $row['isOnLoan'], $row['loanEndDate'], $row['borrowerId']),
            $this->storage->getBookStocks()
        );

        return array_filter(
            $stocks,
            fn(BookStock $bs) => $bs->isOnLoan
        );
    }

    public function getBorrowedBookById(int $bookId, int $borrowerId): ?BookStock
    {
        $stocks = array_map(
            fn(array $row) => new BookStock($row['id'], $row['bookId'], $row['isOnLoan'], $row['loanEndDate'], $row['borrowerId']),
            $this->storage->getBookStocks()
        );
        /**
         * php 8.4 introduced array_find, which is more efficient than this loop, and of course, when we have a real database
         * we do not need those strange filtering and mapping as those can be achieved by crafted queries/ORM
         */
        foreach ( $stocks as $stock){
            if($stock->id === $bookId && $stock->borrowerId === $borrowerId){
                return $stock;
            }
        }

        return null;
    }

    public function addFine(Fine $fine): void
    {
        $this->fines[] = $fine;
    }

    public function removeLoanFromBook(Book $book): void
    {

    }


}

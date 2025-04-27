<?php
namespace App\Repository;


use App\Interfaces\EntityInterface;
use App\Models\BookStock;
use DateTimeImmutable;

class BookStockRepository extends AbstractRepository
{
    public function getBockStockForIdAndBorrower(int $bookId, int $borrowerId): ?BookStock
    {
        /**
         * php 8.4 introduced array_find, which is more efficient than this loop, and of course, when we have a real database
         * we do not need those strange filtering and mapping as those can be achieved by crafted queries/ORM
         */
        foreach ($this->storage->getBookStocks() as $stock){
            if($stock->id === $bookId && $stock->borrowerId === $borrowerId){
                return $stock;
            }
        }

        return null;
    }

    public function removeLoanFromBook(BookStock $bookStock): void
    {
        $bookStock->isOnLoan = false;
        $bookStock->borrowerId = null;
    }

    public function getBockStockForABook(int $bookId): ?BookStock
    {
        foreach ($this->storage->getBookStocks() as $stock){
            if($stock->bookId === $bookId){
                return $stock;
            }
        }

        return null;
    }

    public function loanBook(BookStock $bookStock, int $borrowerId): void
    {
        $bookStock->isOnLoan = true;
        $bookStock->borrowerId = $borrowerId;
    }

    public function insertBookStock(BookStock $bookStock): void
    {
        $bookStocks = $this->storage->getBookStocks();
        $bookStocks[] = $bookStock;
        $this->storage->setBookStocks($bookStocks);
    }

    public function getAvailabilityForBook(int $bookId): ?EntityInterface
    {

        $reservationsRepository = new ReservationsRepository();
        $latestReservation = $reservationsRepository->getLatestReservationForBook($bookId);
        $bookRepository = new BookRepository();
        $book = $bookRepository->findById($bookId, $bookRepository->getBooks());
        if(!$book){
            return null;
        }
        if($latestReservation){
            $reservedAt = DateTimeImmutable::createFromFormat('!Y-m-d', $latestReservation->reservedAt);
            try {
                $availableAt = $reservedAt->modify(sprintf('+%d days', BookStock::LOAN_DURATION_IN_DAYS));
            } catch (\DateMalformedStringException $e) {
                //log exception
            }
            $book->availableAt = $availableAt->format('Y-m-d');

        }else{
            $bookStockRepository = new BookStockRepository();
            $bookStock = $bookStockRepository->getBockStockForABook($bookId);
            $availableAt = (new DateTimeImmutable('today'))->format('Y-m-d');
            if($bookStock->isOnLoan){
                $availableAt = $bookStock->loanEndDate;
            }
            $book->availableAt = $availableAt;

        }

        return $book;
    }

}

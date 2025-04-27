<?php

namespace App\Services;

use App\Models\BookStock;
use App\Models\Fine;
use App\Models\Reservation;
use App\Repository\BookRepository;
use App\Repository\BookStockRepository;
use App\Repository\FineRepository;
use App\Repository\ReservationsRepository;
use DateTimeImmutable;
use Exception;

class BookStockService
{
    public function returnBook(int $bookId, int $borrowerId): bool
    {

        $bookStockRepository = new BookStockRepository();
        $bookStock = $bookStockRepository->getBockStockForIdAndBorrower($bookId, $borrowerId);

        if(!$bookStock){
            // log info
            return false;
        }

        try{
            $today = new DateTimeImmutable('today');
            $due = DateTimeImmutable::createFromFormat('!Y-m-d', $bookStock->loanEndDate);
            // use global exception but they should be narrowed
        }catch (Exception $exception){
            // log/throw exception here
            return false;
        }


        /**
         * Removing a book from loan and applying fine should be handled using a DB transaction mechanism
         * to prevent scenarios, for example, when fine was applied but book stock data not updated which may
         * result in applying another fine for a borrower. (transactions are commonly used in a banking system)
         * If any operation during transaction fails, all data will be reverted to initial state before transaction.
         */

        if($today > $due){
            $days = (int) $today->diff($due)->format('%a');
            /**
             * Depending on case do we want to charge for the day the book is returned
             * In this case, we are not charging
             */
            $amount = Fine::DAILY * ($days - 1);
            $fine = new Fine(
                id:1,
                borrowerId: $borrowerId,
                amount: $amount,
                details: sprintf(
                    'Fine for book with id %d. Book returned on %s. Loan ended on %s. Days overdue %s',
                    $bookStock->bookId, $today->format('Y-m-d'), $bookStock->loanEndDate, $days -1
                )
            );
            $fineRepository = new FineRepository();
            $fineRepository->addFine($fine);
        }

        $bookStockRepository->removeLoanFromBook($bookStock);

        return true;
    }

    public function reserveTheBook(int $bookId, int $borrowerId): bool
    {
        //1. check is book
        $bookRepository = new BookRepository();
        $book = $bookRepository->findById($bookId, $bookRepository->getBooks());
        if(!$book){
            return false;
        }
        //2.check is book onLoan
        $bookStockRepository = new BookStockRepository();
        $bookStock = $bookStockRepository->getBockStockForABook($bookId);
        if(!$bookStock){
            /**
             * create book stock record
             * using random for id as do not have a mechanism which allows generating the id
             * automatically like db does internally,
             * However, with rand collisions may happen
             */
            $bookStock = new BookStock(
                id: rand(1000, 100000),
                bookId: $bookId,
                isOnLoan: false,
                loanEndDate: null,
                borrowerId: $borrowerId,
            );
            $bookStockRepository->insertBookStock($bookStock);
        }

        $today = new DateTimeImmutable('today');
        $duration = sprintf('+%d days', BookStock::LOAN_DURATION_IN_DAYS);
        try {
            $loanEndDate = $today->modify($duration);
        } catch (\DateMalformedStringException $e) {
            // log exception
        }
        //3.if book free reserve from today
        if(!$bookStock->isOnLoan){
            $bookStock->loanEndDate = $loanEndDate->format('Y-m-d');
            $bookStockRepository->loanBook($bookStock, $borrowerId);

            return true;
        }else{
            //4. if on loan, load reservations and check is already reserved
            $reservationsRepository = new ReservationsRepository();
            $latestReservation = $reservationsRepository->getLatestReservationForBook($bookId);

            if(!$latestReservation){
                //5. if not reserve the book from the date loan ends
                $reservation = new Reservation(
                    id: rand(1000, 100000),
                    bookId: $bookId,
                    borrowerId: $borrowerId,
                    reservedAt: $today->format('Y-m-d'),
                );
                $reservationsRepository->insertReservation($reservation);

                return true;
            }else{
                $reservedTill = DateTimeImmutable::createFromFormat('!Y-m-d', $latestReservation->reservedAt);
                try {
                    $newAvailableDateToLoan = $reservedTill->modify($duration);
                } catch (\DateMalformedStringException $e) {
                    //log exception
                }

                try {

                    $reservation = new Reservation(
                        id: rand(1000, 100000),
                        bookId: $bookId,
                        borrowerId: $borrowerId,
                        reservedAt: $newAvailableDateToLoan->format('Y-m-d'),
                    );

                    $reservationsRepository->insertReservation($reservation);

                    return true;
                } catch (\DateMalformedStringException $e) {
                    // log exception
                }

            }

        }

        return false;
    }
}

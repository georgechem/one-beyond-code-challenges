<?php
namespace App\Repository;


use App\Models\Reservation;
use DateTime;

class ReservationsRepository extends AbstractRepository
{
    public function getReservations(): array
    {
        return $this->storage->getReservations();
    }

    public function getLatestReservationForBook(int $bookId): ?Reservation
    {
        $reservations = $this->storage->getReservations();
        $latest = null;
        $latestDate = null;
        foreach ($reservations as $reservation) {
            $loanEdDate = DateTime::createFromFormat('Y-m-d', $reservation->reservedAt);
            if($reservation->bookId === $bookId && ($latestDate === null || $loanEdDate > $latestDate)){
                $latest = $reservation;
                $latestDate = $loanEdDate;
            }
        }

        return $latest;
    }

    public function insertReservation(Reservation $reservation): void {
        $reservations = $this->storage->getReservations();
        $reservations[] = $reservation;
        $this->storage->setReservations($reservations);
    }
}

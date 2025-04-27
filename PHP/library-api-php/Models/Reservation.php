<?php
namespace App\Models;
use App\Interfaces\EntityInterface;

class Reservation implements EntityInterface {
    public int $id;
    public int $bookId;
    public int $borrowerId;
    public string $reservedAt;

    public function __construct(int $id, int $bookId, int $borrowerId, string $reservedAt) {
        $this->id = $id;
        $this->bookId = $bookId;
        $this->borrowerId = $borrowerId;
        $this->reservedAt = $reservedAt;
    }
}

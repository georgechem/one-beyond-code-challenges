<?php
namespace App\Models;
class BookStock {
    public int $id;
    public int $bookId;
    public bool $isOnLoan;
    /**
     * date string type left, but it should be used DateTimeInterface
     */
    public ?string $loanEndDate;  // Date string in 'Y-m-d' format or null
    public ?int $borrowerId;   // null if not on loan

    public function __construct(int $id, int $bookId, bool $isOnLoan = false, ?string $loanEndDate = null, ?int $borrowerId = null) {
        $this->id = $id;
        $this->bookId = $bookId;
        $this->isOnLoan = $isOnLoan;
        $this->loanEndDate = $loanEndDate;
        $this->borrowerId = $borrowerId;
    }
}

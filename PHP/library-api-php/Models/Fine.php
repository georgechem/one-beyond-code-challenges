<?php
namespace App\Models;

class Fine {
    public int $id;
    public int $borrowerId;
    public float $amount;
    public string $details;

    public function __construct(int $id, int $borrowerId, float $amount, string $details = '') {
        $this->id = $id;
        $this->borrowerId = $borrowerId;
        $this->amount = $amount;
        $this->details = $details;
    }
}

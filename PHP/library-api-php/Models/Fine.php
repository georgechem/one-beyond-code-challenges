<?php
namespace App\Models;

use App\Interfaces\EntityInterface;

class Fine implements EntityInterface {

    public const float DAILY = 1.0;

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

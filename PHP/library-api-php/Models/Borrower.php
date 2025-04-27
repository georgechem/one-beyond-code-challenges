<?php
namespace App\Models;

use App\Interfaces\BorrowerInterface;

class Borrower implements BorrowerInterface
{
    public int $id;
    public string $name;
    public string $email;

    public function __construct(int $id, string $name, string $email) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }
}

<?php
namespace App\Repository;

use App\Data\Data;

class LoanRepository extends AbstractRepository
{
    public function getBooks(): array
    {
        return $this->storage->getBooks();
    }
}

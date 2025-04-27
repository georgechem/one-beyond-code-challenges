<?php
namespace App\Repository;

use App\Models\Book;
use App\Models\BookStock;
use App\Models\Fine;

class BookStockRepository extends AbstractRepository
{
    public function addFine(Fine $fine): void
    {
        $fines = $this->storage->getFines();
        $fines[] = $fine;
        $this->storage->setFines($fines);
    }
}

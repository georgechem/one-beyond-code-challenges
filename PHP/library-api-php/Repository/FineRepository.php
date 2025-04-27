<?php
namespace App\Repository;

use App\Models\Fine;

class FineRepository extends AbstractRepository
{
    public function addFine(Fine $fine): void
    {
        $fines = $this->storage->getFines();
        $fines[] = $fine;
        $this->storage->setFines($fines);
    }
}

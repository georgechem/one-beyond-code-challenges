<?php
namespace App\Repository;


class BorrowerRepository extends AbstractRepository
{
    public function getBorrowers(): array
    {
        return $this->storage->getBorrowers();
    }
}

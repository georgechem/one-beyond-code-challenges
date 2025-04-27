<?php
namespace App\Repository;


class BookRepository extends AbstractRepository
{
    public function getBooks(): array
    {
        return $this->storage->getBooks();
    }

}

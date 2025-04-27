<?php
namespace App\Repository;


use App\Models\Book;

class BookRepository extends AbstractRepository
{
    public function getBooks(): array
    {
        return $this->storage->getBooks();
    }

}

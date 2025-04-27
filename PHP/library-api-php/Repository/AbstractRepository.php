<?php

namespace App\Repository;

use App\Data\Data;
use App\Interfaces\EntityInterface;

abstract class AbstractRepository
{
    protected Data $storage;

    public function __construct()
    {
        $this->storage = Data::get();
    }

    public function findById(int $id, array $data): ?EntityInterface
    {
        $found = null;
        foreach ($data as $book) {
            if ($book->id === $id) {
                $found = $book;
                break;
            }
        }

        return $found;
    }
}

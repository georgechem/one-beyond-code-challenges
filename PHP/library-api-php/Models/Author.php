<?php
namespace App\Models;
use App\Interfaces\EntityInterface;

class Author implements EntityInterface {
    public int $id;
    public string $name;

    public function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
    }
}

<?php
namespace App\Models;
use App\Interfaces\EntityInterface;

class Book implements EntityInterface {
    public int $id;
    public string $title;
    public int $authorId;
    public string $format;
    public string $isbn;

    public function __construct(int $id, string $title, string $authorId, string $format, string $isbn) {
        $this->id = $id;
        $this->title = $title;
        $this->authorId = $authorId;
        $this->format = $format;
        $this->isbn = $isbn;
    }
}

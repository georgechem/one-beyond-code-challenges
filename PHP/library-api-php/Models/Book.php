<?php
namespace App\Models;
class Book {
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

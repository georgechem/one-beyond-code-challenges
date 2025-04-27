<?php
namespace App\Data;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookStock;
use App\Models\Borrower;

const ENABLE_STORAGE = 1;

/**
 * Using a singleton pattern to store data
 * (singleton allows us to use the same instance of data in multiple classes)
 * and protect against creating another instance of the class.
 */
class Data {

    private static ?Data $instance = null;

    /**
     * @var Author[]|array
     */
    private array $authors;
    /**
     * @var Book[]|array
     */
    private array $books;
    /**
     * @var Borrower[]|array
     */
    private array $borrowers = [];
    private array $bookStocks = [];
    private array $fines = [];
    private array $reservations = [];
    private const string STORAGE_KEY = './library_api_php.json';

    private const string AUTHOR = 'authors';
    private const string BOOK = 'books';
    private const string BOOK_STOCK = 'bookStocks';
    private const string BORROWER = 'borrowers';
    private const string FINE = 'fines';
    private const string RESERVATION = 'reservations';


    /**
     * Setting the initial state of the app in the constructor.
     */
    private function __construct() {

        if(!file_exists(self::STORAGE_KEY)) {
            $this->assignTheState();
        }else{
            $this->assignTheState(false);
        }

    }

    private function assignTheState(bool $initial = true): void
    {
        if($initial || !ENABLE_STORAGE){
            $this->authors = [
                new Author(1, 'Jane Austen'),
                new Author(2, 'Mark Twain')
            ];

            $this->books = [
                new Book(1, 'Pride and Prejudice', 1, 'Hardcover', '1111111111111'),
                new Book(2, 'Adventures of Huckleberry Finn', 2, 'Paperback', '2222222222222')
            ];

            $this->borrowers = [
                new Borrower(1, 'Alice', 'alice@example.com'),
                new Borrower(2, 'Bob', 'bob@example.com')
            ];

            $this->bookStocks = [
                new BookStock(1, 1, true, '2025-04-10', 1),
                new BookStock(2, 2, false)
            ];

            $this->fines = [];
            $this->reservations = [];

            if(ENABLE_STORAGE) {
                $this->persistTheCurrentState();
            }

        }
        else{
            $data = file_get_contents(self::STORAGE_KEY);
            try{
                $array = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
                foreach ($array as $key => $value) {
                    if(property_exists($this, $key)){
                        $this->{$key} = $value;
                    }
                }
            }catch(\JsonException $e){
                var_dump(sprintf('Error: %s', $e->getMessage()));
            }

        }

    }

    public function persistTheCurrentState(): void
    {
        $stateString = json_encode([
            self::AUTHOR => $this->authors,
            self::BOOK => $this->books,
            self::BOOK_STOCK => $this->bookStocks,
            self::BORROWER => $this->borrowers,
            self::FINE => $this->fines,
            self::RESERVATION => $this->reservations,
        ]);

        file_put_contents(self::STORAGE_KEY, $stateString);
    }

    public static function get(): self
    {
        if(self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function getBooks(): array
    {
        return $this->books;
    }

    public function getBorrowers(): array
    {
        return $this->borrowers;
    }

    public function getBookStocks(): array
    {
        return $this->bookStocks;
    }

    public function getFines(): array
    {
        return $this->fines;
    }

    public function getReservations(): array
    {
        return $this->reservations;
    }
}

<?php
namespace App\Data;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookStock;
use App\Models\Borrower;
use App\Models\Fine;
use App\Models\Reservation;

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
    public array $authors;
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
            $this->assignTheInitialState();
        }else{
            $this->assignTheInitialState(false);
        }

    }

    private function assignTheInitialState(bool $initial = true): void
    {
        if($initial){
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
            $this->reservations = [
                //new Reservation(1, 1, 1, '2025-04-27'),
            ];

            if(ENABLE_STORAGE) {
                $this->save();
            }

        }
        else{

            try{
                $raw = json_decode(
                    file_get_contents(self::STORAGE_KEY),
                    false,                      // decode into stdClass, not assoc arrays
                    512,
                    JSON_THROW_ON_ERROR
                );

                /**
                 * load from JSON and hydrate into model objects,
                 * in PHP objects are returned by reference so that allow us to mutate
                 * them directly
                 */
                $this->authors = array_map(
                    fn(\stdClass $author) => new Author($author->id, $author->name),
                    $raw->{self::AUTHOR} ?? []
                );

                $this->books = array_map(
                    fn(\stdClass $book) => new Book(
                        $book->id,
                        $book->title,
                        $book->authorId,
                        $book->format,
                        $book->isbn
                    ),
                    $raw->{self::BOOK} ?? []
                );

                $this->borrowers = array_map(
                    fn(\stdClass $borrower) => new Borrower($borrower->id, $borrower->name, $borrower->email),
                    $raw->{self::BORROWER} ?? []
                );

                $this->bookStocks = array_map(
                    fn(\stdClass $bookStock) => new BookStock(
                        $bookStock->id,
                        $bookStock->bookId,
                        $bookStock->isOnLoan,
                        $bookStock->loanEndDate,
                        $bookStock->borrowerId
                    ),
                    $raw->{self::BOOK_STOCK} ?? []
                );

                $this->fines = array_map(
                    fn(\stdClass $fine) => new Fine(
                        $fine->id,
                        $fine->borrowerId,
                        $fine->amount,
                        $fine->details
                    ),
                    $raw->{self::FINE} ?? []
                );

                $this->reservations = array_map(
                    fn(\stdClass $reservation) => new Reservation(
                        $reservation->id,
                        $reservation->bookId,
                        $reservation->borrowerId,
                        $reservation->reservedAt
                    ),
                    $raw->{self::RESERVATION} ?? []
                );

            }catch(\JsonException $e){
                var_dump(sprintf('Error: %s', $e->getMessage()));
            }

        }

    }

    public function save(): void
    {
        if(!ENABLE_STORAGE) {
            return;
        }

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

    public function setFines(array $fines): void
    {
        $this->fines = $fines;
    }

    public function setBookStocks(array $bookStocks): void
    {
        $this->bookStocks = $bookStocks;
    }

    public function setReservations(array $reservations): void
    {
        $this->reservations = $reservations;
    }
}

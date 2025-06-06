<?php

namespace App\Validator\Constraints;

use App\Interfaces\ConstraintInterface;

/**
 * Constraints can be atomic, made one bigger just for simplicity
 */
class BookIdBorrowerIdConstraint implements ConstraintInterface
{

    private array $errors = [];
    private array $values = [];

    public const string NAME = 'BOOK_ID_BORROWER_ID';

    public function getName(): string
    {
        return self::NAME;
    }

    public function validate(): self
    {
        if (empty($_GET['bookId'])) {
            $this->errors[] = 'Book id is required.';
        }
        if(empty($_GET['borrowerId'])){
            $this->errors[] = 'Borrower id is required.';
        }

        $this->values['borrowerId'] = (int) $_GET['borrowerId'];
        $this->values['bookId'] = (int) $_GET['bookId'];

        return $this;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}

<?php

namespace App\Validator\Constraints;

use App\Interfaces\ConstraintInterface;
use App\Response\JsonResponse;

class ReturnBookConstraint implements ConstraintInterface
{

    private array $errors = [];
    private array $values = [];

    public const string NAME = 'RETURN_BOOK';

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

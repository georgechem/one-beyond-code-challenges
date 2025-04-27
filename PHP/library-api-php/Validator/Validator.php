<?php

namespace App\Validator;

use App\Interfaces\ConstraintInterface;
use App\Validator\Constraints\BookIdBorrowerIdConstraint;
use App\Validator\Constraints\BookIdConstraint;

class Validator
{
    /**
     * Here, using match as app is small but in a large-scale application
     * strategy pattern or chain of responsibility should be used for validation
     *
     */
    public static function validate(ConstraintInterface $constraint): ?ConstraintInterface
    {
        return match ($constraint->getName()) {
            BookIdBorrowerIdConstraint::NAME, BookIdConstraint::NAME => $constraint->validate(),
            default => null,
        };
    }
}

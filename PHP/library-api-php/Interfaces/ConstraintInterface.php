<?php

namespace App\Interfaces;

interface ConstraintInterface
{
    public function getName(): string;
    public function validate(): self;
    public function getErrors(): array;
    public function getValues(): array;

}

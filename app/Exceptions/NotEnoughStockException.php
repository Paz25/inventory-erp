<?php

namespace App\Exceptions;

use Exception;

class NotEnoughStockException extends Exception
{
    public function __construct(
        public readonly int $available,
    ) {
        parent::__construct(
            "Not enouugh stock, only {$available} available."
        );
    }
}

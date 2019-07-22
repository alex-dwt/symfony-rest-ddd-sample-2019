<?php

namespace App\Application\Exception;

class ForbiddenException extends \Exception
{
    public function __construct(string $message = 'Forbidden')
    {
        parent::__construct($message);
    }
}

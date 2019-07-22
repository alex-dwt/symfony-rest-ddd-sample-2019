<?php

namespace App\Domain\User\Exception;

class UserCanNotInviteHimselfException extends \DomainException
{
    public function __construct(string $message = "User can not invite himself", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
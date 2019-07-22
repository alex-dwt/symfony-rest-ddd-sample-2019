<?php

namespace App\Domain\User\Exception;

class UserCanNotInviteFriendException extends \DomainException
{
    public function __construct(string $message = "User can not invite friend", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
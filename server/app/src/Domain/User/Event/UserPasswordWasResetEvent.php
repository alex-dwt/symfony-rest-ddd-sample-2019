<?php

namespace App\Domain\User\Event;

use App\Application\Event\Event;
use App\Domain\User\User;

class UserPasswordWasResetEvent extends Event
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $newPassword;

    /**
     * @var User|null
     */
    private $user;

    public function __construct(User $user, string $newPassword)
    {
        $this->user = $user;
        $this->userId = $user->getId();
        $this->newPassword = $newPassword;
    }

    public function user(): ?User
    {
        return $this->user;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function newPassword(): string
    {
        return $this->newPassword;
    }

    public function saveToDb(): Event
    {
        unset($this->user);

        return $this;
    }

    public function eventWasProcessed(bool $isSuccess)
    {
        $this->newPassword = '***';
    }
}

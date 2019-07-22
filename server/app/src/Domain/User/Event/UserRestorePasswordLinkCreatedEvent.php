<?php

namespace App\Domain\User\Event;

use App\Application\Event\Event;
use App\Domain\User\User;

class UserRestorePasswordLinkCreatedEvent extends Event
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var User|null
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->userId = $user->getId();
    }

    public function user(): ?User
    {
        return $this->user;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function saveToDb(): Event
    {
        unset($this->user);

        return $this;
    }
}

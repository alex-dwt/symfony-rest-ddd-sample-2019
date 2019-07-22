<?php

namespace App\Domain\Friend\Event;

use App\Application\Event\Event;
use App\Domain\Friend\FriendInvite;
use App\Domain\Tournament\Tournament;

class FriendInviteCreatedEvent extends Event
{
    /**
     * @var string
     */
    private $friendInviteId;

    /**
     * @var FriendInvite|null
     */
    private $friendInvite;

    public function __construct(FriendInvite $friendInvite)
    {
        $this->friendInvite = $friendInvite;
        $this->friendInviteId = $friendInvite->getId();
    }

    public function friendInviteId(): string
    {
        return $this->friendInviteId;
    }

    public function friendInvite(): ?FriendInvite
    {
        return $this->friendInvite;
    }

    public function saveToDb(): Event
    {
        unset($this->friendInvite);

        return $this;
    }
}

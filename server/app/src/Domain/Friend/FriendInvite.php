<?php

namespace App\Domain\Friend;

use App\Application\Event\EventsDispatcherStatic;
use App\Domain\Announcement\Event\AnnouncementCreatedEvent;
use App\Domain\Common\InitEntityTrait;
use App\Domain\Friend\Event\FriendInviteCreatedEvent;
use App\Domain\User\User;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(
 *    name="friend_invites",
 *    indexes={
 *          @ORM\Index(name="createdat_idx", columns={"created_at"})
 *    },
 *    uniqueConstraints={@ORM\UniqueConstraint(name="users_idx", columns={"author_id", "user_to_invite_id"})}
 * )
 */
class FriendInvite
{
    use InitEntityTrait;

    /**
     * @var User
     * @ORM\ManyToOne(
     *     fetch="EAGER",
     *     targetEntity="App\Domain\User\User",
     *     inversedBy="friendInvitesOutgoing"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="cascade")
     */
    private $author;

    /**
     * @var User
     * @ORM\ManyToOne(
     *     fetch="EAGER",
     *     targetEntity="App\Domain\User\User",
     *     inversedBy="friendInvitesIncoming"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="cascade")
     */
    private $userToInvite;

    public function __construct(
        User $author,
        User $userToInvite
    ) {
        $this->init();

        $this->author = $author;
        $this->userToInvite = $userToInvite;

        EventsDispatcherStatic::getInstance()->dispatch(
            new FriendInviteCreatedEvent($this)
        );
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getUserToInvite(): User
    {
        return $this->userToInvite;
    }
}

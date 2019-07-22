<?php

namespace App\Domain\User;

use App\Application\Event\EventsDispatcherStatic;
use App\Domain\Common\InitEntityTrait;
use App\Domain\Friend\FriendInvite;
use App\Domain\Tournament\Game;
use App\Domain\User\Event\UserCreatedEvent;
use App\Domain\User\Event\UserPasswordWasResetEvent;
use App\Domain\User\Event\UserRestorePasswordLinkCreatedEvent;
use App\Domain\User\Exception\UserCanNotInviteFriendException;
use App\Domain\User\Exception\UserCanNotInviteHimselfException;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(
 *    name="users"
 * )
 */
class User implements UserInterface
{
    use InitEntityTrait;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $nickname;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $avatarUrl = '';

    /**
     * @ORM\Column(type="string")
     */
    private $sessionId = '';

    /**
     * @ORM\Embedded(class="UserSettings")
     * @var UserSettings
     */
    private $settings;

    /**
     * @ORM\Embedded(class="UserPasswordRestoreLink")
     * @var UserPasswordRestoreLink
     */
    private $passwordRestoreLink;

    /**
     * @var Collection|User[]
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(
     *      name="user_friends",
     *      joinColumns={@ORM\JoinColumn(name="user_source", referencedColumnName="id", onDelete="cascade")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_target", referencedColumnName="id", onDelete="cascade")}
     *
     * )
     */
    private $friends;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Friend\FriendInvite",
     *     mappedBy="author",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     * @var Collection|FriendInvite[]
     */
    private $friendInvitesOutgoing;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Friend\FriendInvite",
     *     mappedBy="userToInvite"
     * )
     * @var Collection|FriendInvite[]
     */
    private $friendInvitesIncoming;

    public function __construct(
        string $nickname,
        string $email,
        string $language,
        string $timezone
    ) {
        $this->init();

        $this->nickname = $nickname;
        $this->email = $email;
        $this->settings = new UserSettings($language, $timezone);
        $this->passwordRestoreLink = new UserPasswordRestoreLink();

        $this->friends = new ArrayCollection();
        $this->friendInvitesOutgoing = new ArrayCollection();
        $this->friendInvitesIncoming = new ArrayCollection();

        EventsDispatcherStatic::getInstance()->dispatch(
            new UserCreatedEvent($this)
        );
    }

    public function getUsername()
    {
        return $this->nickname;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return [];
    }

    public function eraseCredentials()
    {
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function regenerateSessionId(): string
    {
        return $this->sessionId = Uuid::uuid4()->toString();
    }

    public function getSettings(): UserSettings
    {
        return $this->settings;
    }

    public function updateSettings(array $params)
    {
        $this->settings = $this->settings->update($params);
    }

    public function createPasswordRestoreLink()
    {
        $this->passwordRestoreLink = new UserPasswordRestoreLink(true);

        EventsDispatcherStatic::getInstance()->dispatch(
            new UserRestorePasswordLinkCreatedEvent($this)
        );
    }

    public function resetPasswordUsingRestoreLink(string $newPassword, string $newPasswordPlain): bool
    {
        if (!$this->passwordRestoreLink->isValid()) {
            return false;
        }

        $this->password = $newPassword;
        $this->passwordRestoreLink = new UserPasswordRestoreLink();

        EventsDispatcherStatic::getInstance()->dispatch(
            new UserPasswordWasResetEvent($this, $newPasswordPlain)
        );

        return true;
    }

    public function getRestoreLinkHash(): ?string
    {
        return $this->passwordRestoreLink->getHash();
    }

    public function inviteFriend(User $user)
    {
        if ($this === $user) {
            throw new UserCanNotInviteHimselfException();
        }

        if ($this->friends->contains($user)) {
            throw new UserCanNotInviteFriendException();
        }

        if ($this
                ->friendInvitesOutgoing
                ->matching(
                    Criteria::create()->andWhere(Criteria::expr()->eq('userToInvite', $user))
                )->count()
        ) {
            return;
        }

        // if you have invites from that user
        // clear all invites and make as friend immediately
        if ($this
            ->friendInvitesIncoming
            ->matching(
                Criteria::create()->andWhere(Criteria::expr()->eq('author', $user))
            )->count()
        ) {
            $this->makeFriendship($user);
        } else {
            $this->friendInvitesOutgoing->add(
                new FriendInvite($this, $user)
            );
        }
    }

    public function acceptFriendInvite(string $inviteId)
    {
        /** @var FriendInvite $invite */
        if (!$invite = $this
                ->friendInvitesIncoming
                ->matching(
                    Criteria::create()
                        ->andWhere(Criteria::expr()->eq('id', $inviteId))
                )
                ->first()
        ) {
            return;
        }

        $this->makeFriendship($invite->getAuthor());
    }

    public function cancelMyFriendInvite(string $inviteId)
    {
        /** @var FriendInvite $invite */
        if (!$invite = $this
                ->friendInvitesOutgoing
                ->matching(
                    Criteria::create()->andWhere(Criteria::expr()->eq('id', $inviteId))
                )
                ->first()
        ) {
            return;
        }

        $this->clearMyInvitesToUser($invite->getUserToInvite());
    }

    /**
     * @return iterable|ArrayCollection
     */
    public function getFriends(): iterable
    {
        return $this->friends;
    }

    public function getFriend(string $id): ?User
    {
        return $this
            ->friends
            ->matching(
                Criteria::create()
                    ->andWhere(Criteria::expr()->eq('id', $id))
            )
            ->first() ?: null;
    }

    public function removeFriend(User $user)
    {
        $this->friends->removeElement($user);
        $user->friends->removeElement($this);
    }

    private function addFriend(User $user)
    {
        if ($this === $user) {
            return;
        }

        if ($this->friends->contains($user)) {
            return;
        }

        $this->friends->add($user);
    }

    private function clearMyInvitesToUser(User $user)
    {
        foreach ($this
            ->friendInvitesOutgoing
            ->matching(
                Criteria::create()->andWhere(Criteria::expr()->eq('userToInvite', $user))
            ) as $invite
        ) {
            $this->friendInvitesOutgoing->removeElement($invite);
        }
    }

    private function makeFriendship(User $user)
    {
        if ($this === $user) {
            return;
        }

        $this->addFriend($user);
        $user->addFriend($this);

        $this->clearMyInvitesToUser($user);
        $user->clearMyInvitesToUser($this);
    }
}

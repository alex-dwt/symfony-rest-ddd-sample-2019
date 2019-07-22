<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Friend\FriendInvite;

class DoctrineFriendInviteRepository extends AbstractDoctrineRepository
{
    public function repositoryClassName(): string
    {
        return FriendInvite::class;
    }
}

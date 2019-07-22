<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\User\User;
use App\Domain\User\UserRepository;

class DoctrineUserRepository extends AbstractDoctrineRepository implements UserRepository
{
    public function repositoryClassName(): string
    {
        return User::class;
    }

    public function getUserFriendsCount(User $user): int
    {
        return (int) $this
            ->createQueryBuilder('user')
            ->select('count(user)')
            ->join('user.friends', 'friends')
            ->andWhere('user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}

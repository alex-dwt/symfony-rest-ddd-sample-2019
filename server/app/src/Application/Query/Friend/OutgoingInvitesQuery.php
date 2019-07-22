<?php

namespace App\Application\Query\Friend;

use App\Application\Query\AbstractQuery;
use App\Domain\Friend\Transformer\FriendInviteTransformer;
use App\Domain\Tournament\Game;
use App\Domain\Tournament\Transformer\GameShortTransformer;
use App\Infrastructure\Persistence\Doctrine\DoctrineFriendInviteRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrineGameRepository;
use Doctrine\ORM\QueryBuilder;

class OutgoingInvitesQuery extends AbstractQuery
{
    public function __construct(
        DoctrineFriendInviteRepository $repository
    ) {
        $this->repo = $repository;
    }

    protected function query()
    {
        return $this
            ->repo
            ->createQueryBuilder('invite')
            ->select([
                'invite',
                'userToInvite',
            ])
            ->join('invite.userToInvite', 'userToInvite')
            ->andWhere('invite.author = :author')
            ->addOrderBy('invite.createdAt', 'DESC')
            ->setParameters([
                'author' => $this->user,
            ])
            ;
    }

    protected function transform(iterable $items): array
    {
        return (new FriendInviteTransformer(false))->transform($items);
    }
}
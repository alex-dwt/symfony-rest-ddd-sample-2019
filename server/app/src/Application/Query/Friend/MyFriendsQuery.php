<?php

namespace App\Application\Query\Friend;

use App\Application\Query\AbstractQuery;
use App\Application\Request\Game\GameCalendarRequest;
use App\Domain\Friend\Transformer\FriendTransformer;
use App\Domain\Tournament\Game;
use App\Domain\Tournament\Transformer\GameShortTransformer;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrineGameRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

class MyFriendsQuery extends AbstractQuery
{
    public function __construct(
        UserRepository $repository
    ) {
        $this->repo = $repository;
    }

    protected function query()
    {
        $items = $this
            ->user
            ->getFriends()
            ->matching(
                Criteria::create()
                    ->orderBy(['nickname' => 'ASC'])
                    ->setFirstResult($this->offset)
                    ->setMaxResults($this->limit)
            )
            ->getIterator();

        return [
            $items,
            $this->repo->getUserFriendsCount($this->user),
        ];
    }

    protected function transform(iterable $items): array
    {
        return (new FriendTransformer())->transform($items);
    }
}
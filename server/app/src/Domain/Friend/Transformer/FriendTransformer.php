<?php

namespace App\Domain\Friend\Transformer;

use App\Domain\Common\DomainTransformer;
use App\Domain\Tournament\Tournament;
use App\Domain\User\Transformer\UserShortTransformer;
use App\Domain\User\User;

class FriendTransformer extends DomainTransformer
{
    /**
     * @param User $entity
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return (new UserShortTransformer())->transform($entity)
            + [
                'countOfMessages' => 0,
                'countOfNewMessages' => 0,
            ];
    }
}

<?php

namespace App\Domain\Friend\Transformer;

use App\Domain\Common\DomainTransformer;
use App\Domain\Friend\FriendInvite;
use App\Domain\Tournament\Tournament;
use App\Domain\User\Transformer\UserShortTransformer;
use App\Domain\User\User;

class FriendInviteTransformer extends DomainTransformer
{
    /**
     * @var bool
     */
    private $isShowAuthorUser;

    public function __construct(bool $isShowAuthorUser)
    {
        $this->isShowAuthorUser = $isShowAuthorUser;
    }

    /**
     * @param FriendInvite $entity
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'createdAt' => $entity->getCreatedAt()->format(\DateTime::ATOM),
            'user' => (new UserShortTransformer())
                ->transform(
                    $this->isShowAuthorUser
                        ? $entity->getAuthor()
                        : $entity->getUserToInvite()
                ),
        ];
    }
}

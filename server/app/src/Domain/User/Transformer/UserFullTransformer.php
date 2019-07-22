<?php

namespace App\Domain\User\Transformer;

use App\Domain\User\User;

class UserFullTransformer extends UserShortTransformer
{
    /**
     * @param User $entity
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return
            parent::transformOneEntity($entity) + [
                'email' => $entity->getEmail(),
            ];
    }
}

<?php

namespace App\Domain\Common;

class ToArrayTransformer extends DomainTransformer
{
    /**
     * @param ToArrayTransformable $entity
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        if (!$entity instanceof ToArrayTransformable) {
            throw new \LogicException();
        }

        return $entity->toArray();
    }
}

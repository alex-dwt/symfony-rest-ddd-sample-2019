<?php

namespace App\Domain\Common;

abstract class DomainTransformer
{
    public function transform($entity)
    {
        if (is_iterable($entity)) {
            $res = [];
            foreach ($entity as $one) {
                $res[] = $this->transformOneEntity($one);
            }

            return $res;
        } else {
            return $this->transformOneEntity($entity);
        }
    }

    abstract protected function transformOneEntity($entity): array;
}

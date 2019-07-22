<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Common\StoredEvent;

class DoctrineStoredEventRepository extends AbstractDoctrineRepository
{
    public function repositoryClassName(): string
    {
        return StoredEvent::class;
    }
}

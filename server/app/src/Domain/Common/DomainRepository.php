<?php

namespace App\Domain\Common;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

interface DomainRepository
{
    public function get(string $id);

    public function add($entity): void;

    public function remove($entity): void;

    public function getCollectionByCriteria(DomainCriteria $criteria): Collection;

    public function getOneByCriteria(DomainCriteria $criteria);

    public function createQueryBuilder(string $alias = ''): QueryBuilder;

    public function getAll(): iterable;
}

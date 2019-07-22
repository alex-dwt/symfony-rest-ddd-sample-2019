<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Common\DomainCriteria;
use App\Domain\Common\DomainRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractDoctrineRepository implements DomainRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        /** @var EntityRepository $repo */
        $repo = $em->getRepository($this->repositoryClassName());

        $this->em = $em;
        $this->repo = $repo;
    }

    abstract public function repositoryClassName(): string;

    public function get(string $id)
    {
        return $this->repo->find($id);
    }

    public function add($entity): void
    {
        $this->em->persist($entity);
    }

    public function remove($entity): void
    {
        $this->em->remove($entity);
    }

    public function getCollectionByCriteria(DomainCriteria $criteria): Collection
    {
        return $this->repo->matching($criteria->create());
    }

    public function getOneByCriteria(DomainCriteria $criteria)
    {
        $res = $this->getCollectionByCriteria($criteria);

        return $res->count()
            ? $res->first()
            : null;
    }

    public function createQueryBuilder(string $alias = ''): QueryBuilder
    {
        return $this->repo->createQueryBuilder($alias);
    }

    public function getAll(): iterable
    {
        return $this->repo->findAll();
    }

    public function getEm(): EntityManagerInterface
    {
        return $this->em;
    }

    protected function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->em->getConnection()->prepare($sql);
        foreach ($params as $name => $val) {
            $stmt->bindValue($name, $val);
        }
        $stmt->execute();

        return $stmt->fetchAll();
    }
}

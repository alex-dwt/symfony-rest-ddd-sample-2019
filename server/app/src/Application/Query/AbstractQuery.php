<?php

namespace App\Application\Query;

use App\Application\Request\PaginationRequest;
use App\Domain\User\User;
use App\Infrastructure\Persistence\Doctrine\AbstractDoctrineRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class AbstractQuery
{
    /**
     * @var AbstractDoctrineRepository|null
     */
    protected $repo;

    /**
     * @var PaginationRequest
     */
    protected $request;

    /**
     * @var User|null
     */
    protected $user;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var int
     */
    protected $timezone;

    /**
     * @var array
     */
    protected $payload;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    public function execute(
        PaginationRequest $request,
        ?User $user,
        array $payload = []
    ): array {
        $this->user = $user;
        $this->request = $request;
        $this->timezone = (int) $request->getAppTimezone();
        $this->language = $request->getAppLanguage();
        $this->payload = $payload;
        $this->limit = $limit = $request->getLimit();
        $this->offset = $offset = $request->getOffset();

        if (is_array($query = $this->query())) {
            [$paginator, $count] = $query;
        } else {
            $query
                ->setMaxResults($limit)
                ->setFirstResult($offset);

            $paginator = new Paginator($query, true);
            $count = count($paginator);
        }

        return [
            'items' => $this->transform($paginator),
            'paging' => compact('count', 'limit', 'offset'),
        ];
    }

    /**
     * @return iterable|QueryBuilder
     */
    abstract protected function query();

    abstract protected function transform(iterable $items): array;
}
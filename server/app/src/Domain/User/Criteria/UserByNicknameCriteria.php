<?php

namespace App\Domain\User\Criteria;

use App\Domain\Common\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class UserByNicknameCriteria implements DomainCriteria
{
    /**
     * @var string
     */
    private $nickname;

    public function __construct(string $nickname)
    {
        $this->nickname = $nickname;
    }

    public function create(): Criteria
    {
        return Criteria::create()
            ->andWhere(
                Criteria::expr()->eq('nickname', $this->nickname)
            );
    }
}
<?php

namespace App\Domain\User\Criteria;

use App\Domain\Common\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class UserByRestorePasswordLinkCriteria implements DomainCriteria
{
    /**
     * @var string
     */
    private $hash;

    public function __construct(string $hash)
    {
        $this->hash = $hash;
    }

    public function create(): Criteria
    {
        return Criteria::create()
            ->andWhere(
                Criteria::expr()->eq('passwordRestoreLink.hash', $this->hash)
            );
    }
}
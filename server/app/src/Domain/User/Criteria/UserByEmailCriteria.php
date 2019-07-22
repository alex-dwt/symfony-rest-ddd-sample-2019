<?php

namespace App\Domain\User\Criteria;

use App\Domain\Common\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class UserByEmailCriteria implements DomainCriteria
{
    /**
     * @var string
     */
    private $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function create(): Criteria
    {
        return Criteria::create()
            ->andWhere(
                Criteria::expr()->eq('email', $this->email)
            );
    }
}
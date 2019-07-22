<?php

namespace App\Domain\Common;

use Doctrine\Common\Collections\Criteria;

interface DomainCriteria
{
    public function create(): Criteria;
}

<?php

namespace App\Application\Exception;

class EntityNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Entity is not found');
    }
}

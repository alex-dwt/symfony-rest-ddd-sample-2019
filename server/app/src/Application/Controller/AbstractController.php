<?php

namespace App\Application\Controller;

use App\Application\Exception\EntityNotFoundException;
use App\Domain\User\User;
use App\Infrastructure\Persistence\Doctrine\AbstractDoctrineRepository;

/**
 * @method User|null getUser()
 */
abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
}
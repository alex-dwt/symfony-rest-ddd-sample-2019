<?php

namespace App\Application\Service\Helper;

use Doctrine\ORM\EntityManagerInterface;

class DbTablesPurger
{
    private const USERS_TABLE = 'users';

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->connection = $em->getConnection();
    }

    public function purgeUsers()
    {
        $this->purgeTable(self::USERS_TABLE);
    }

    private function purgeTable(string $tableName)
    {
        $this->connection->executeQuery("DELETE FROM $tableName");
    }
}

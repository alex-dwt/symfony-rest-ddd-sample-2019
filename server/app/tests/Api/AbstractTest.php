<?php

namespace App\Tests\Api;

use App\Application\Command\FixturesCommand;
use App\Application\Service\Helper\DbTablesPurger;
use App\Application\Service\Helper\EntitiesCreator;
use App\Domain\User\Criteria\UserByNicknameCriteria;
use App\Domain\User\User;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractTest extends KernelTestCase
{
    use
        HttpClientTrait,
        AssertsTrait;

    /**
     * @var User
     */
    protected static $initialUser;

    /**
     * @var string|null
     */
    protected $loggedUserToken;

    /**
     * @var User|null
     */
    protected $loggedUserEntity;

    /**
     * @var EntityManagerInterface
     */
    protected static $em;

    public static function setUpBeforeClass()
    {
        if (!static::$kernel) {
            self::bootKernel(['environment' => 'test']);
            self::$em = self::$container->get('doctrine.orm.default_entity_manager');
            $GLOBALS['kernel'] = static::$kernel;
        }

        self::recreateUser();
    }

    protected static function flush()
    {
        self::$em->flush();
        self::$em->clear();
    }

    protected static function recreateUser()
    {
        self::$container->get(DbTablesPurger::class)->purgeUsers();

        self::$initialUser = self::$container->get(EntitiesCreator::class)->createUser(
            FixturesCommand::NICKNAME,
            FixturesCommand::EMAIL
        );

        self::flush();
    }

    protected function tearDown()
    {
        self::$em->clear();

        $this->loggedUserToken = null;
        $this->loggedUserEntity = null;
    }

    protected function login()
    {
        $user = self::$container
            ->get(DoctrineUserRepository::class)
            ->getOneByCriteria(new UserByNicknameCriteria(FixturesCommand::NICKNAME));

        $this->loggedUserEntity = $user;

        return $this->loggedUserToken = self::$container
            ->get('lexik_jwt_authentication.jwt_manager')
            ->create($user);
    }

    protected static function makeFriendship(User $user1, User $user2)
    {
        $conn = self::$em->getConnection();
        $conn->executeQuery("INSERT INTO user_friends VALUES ('{$user1->getId()}', '{$user2->getId()}'), ('{$user2->getId()}', '{$user1->getId()}')");
    }
}

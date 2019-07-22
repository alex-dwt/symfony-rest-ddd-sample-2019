<?php

namespace App\Application\Command;

use App\Application\Service\Helper\DbTablesPurger;
use App\Application\Service\Helper\EntitiesCreator;
use App\Domain\User\User;
use App\Infrastructure\Persistence\Doctrine\AbstractDoctrineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class FixturesCommand extends Command  implements ContainerAwareInterface
{
    const NICKNAME = 'user';
    const EMAIL = 'user@example.com';
    const PASSWORD = '123';

    private const TABLES = [
        'users'
    ];

    use ContainerAwareTrait;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var DbTablesPurger
     */
    private $dbTablesPurger;

    /**
     * @var EntitiesCreator
     */
    private $entitiesCreator;

    public function __construct(
        EntityManagerInterface $em,
        DbTablesPurger $dbTablesPurger,
        EntitiesCreator $entitiesCreator
    ) {
        $this->em = $em;
        $this->dbTablesPurger = $dbTablesPurger;
        $this->entitiesCreator = $entitiesCreator;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:fixtures')
            ->setDescription('Fixtures')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $progressBar = new ProgressBar($output, 100);
        $progressBar->start();


        $this->dbTablesPurger->purgeUsers();
        $progressBar->advance();

        /**
         * Users
         */
        /** @var User[] $users */
        $users = [];
        foreach (range(1, 3) as $id) {
            $name = self::NICKNAME . $id;
            $users[] = $this->entitiesCreator->createUser(
                $name,
                $name . '@example.com'
            );
            $progressBar->advance();
        }

        /**
         * All as friends
         */
        foreach ($users as $user1) {
            $method = new \ReflectionMethod($user1, 'makeFriendship');
            $method->setAccessible(true);

            foreach ($users as $user2) {
                $method->invoke($user1, $user2);
            }
        }

        $this->em->flush();

        $progressBar->finish();
    }
}
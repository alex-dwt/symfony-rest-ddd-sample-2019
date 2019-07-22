<?php

namespace App\Application\Service\Helper;

use AlexDwt\VerifiedRequestBundle\Request\VerifiedRequest;
use App\Application\Handler\User\RegisterUserHandler;
use App\Application\Request\User\RegisterUserRequest;
use App\Domain\User\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntitiesCreator
{
    const DEFAULT_PASSWORD = '123';

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(
        ContainerInterface $container
    ) {
        $this->container = $container;
    }

    public function createUser(
        string $nickname,
        string $email,
        string $password = self::DEFAULT_PASSWORD,
        string $referer = ''
    ): User {
        /** @var callable $handler */
        $handler = $this->container->get(RegisterUserHandler::class);

        /** @var RegisterUserRequest $request */
        $request = $this->createRequest(RegisterUserRequest::class, [
            'nickname' => $nickname,
            'email' => $email,
            'referer' => $referer,
            'language' => 'ru',
            'timezone' => '0',
            'password' => $password,
        ]);

        return $handler($request);
    }

    private function createRequest(string $className, array $params): VerifiedRequest
    {
        /** @var VerifiedRequest $request */
        $request = $this
            ->container
            ->get($className);

        return $request
            ->populateFromArray(array_merge(
                $params,
                [
                    'appLanguage' => 'ru',
                    'appTimezone' => '0',
                ]
            ));
    }
}

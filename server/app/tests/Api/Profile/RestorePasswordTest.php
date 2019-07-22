<?php

namespace App\Tests\Api\Profile;

use App\Application\Command\FixturesCommand;
use App\Domain\User\Criteria\UserByNicknameCriteria;
use App\Domain\User\User;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use App\Tests\Api\AbstractTest;
use App\Tests\Api\Urls;

class RestorePasswordTest extends AbstractTest
{
    public function testRequestRestorePasswordByFake()
    {
        $this->put(
            Urls::PROFILE_RESTORE_PASSWORD_URL,
            [
                'identity' => '____',
            ],
            204
        );
    }

    public function testRequestRestorePasswordByNickname()
    {
        $this->put(
            Urls::PROFILE_RESTORE_PASSWORD_URL,
            [
                'identity' => FixturesCommand::NICKNAME,
            ],
            204
        );

        /** @var User $user */
        $user = self::$container
            ->get(DoctrineUserRepository::class)
            ->getOneByCriteria(new UserByNicknameCriteria(FixturesCommand::NICKNAME));

        $this->assertInternalType('string', $user->getRestoreLinkHash());
    }

    public function testRequestRestorePasswordByEmail()
    {
        self::recreateUser();

        $this->put(
            Urls::PROFILE_RESTORE_PASSWORD_URL,
            [
                'identity' => FixturesCommand::EMAIL,
            ],
            204
        );

        /** @var User $user */
        $user = self::$container
            ->get(DoctrineUserRepository::class)
            ->getOneByCriteria(new UserByNicknameCriteria(FixturesCommand::NICKNAME));

        $this->assertInternalType('string', $user->getRestoreLinkHash());
    }

    /**
     * @depends testRequestRestorePasswordByEmail
     */
    public function testRestorePasswordConfirmationSuccess()
    {
        /** @var User $user */
        $user = self::$container
            ->get(DoctrineUserRepository::class)
            ->getOneByCriteria(new UserByNicknameCriteria(FixturesCommand::NICKNAME));

        $this->get(
            Urls::PROFILE_RESTORE_PASSWORD_CONFIRMATION_URL,
            200,
            [
                'hash' => (string) $user->getRestoreLinkHash(),
                'id' => (string) $user->getId(),
            ]
        );
    }

    public function testRestorePasswordConfirmationFailed()
    {
        $this->get(
            Urls::PROFILE_RESTORE_PASSWORD_CONFIRMATION_URL,
            422,
            [
                'hash' => '___',
            ]
        );
    }
}

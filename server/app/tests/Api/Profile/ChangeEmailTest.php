<?php

namespace App\Tests\Api\Profile;

use App\Application\Command\FixturesCommand;
use App\Application\Service\Helper\EntitiesCreator;
use App\Tests\Api\AbstractTest;
use App\Tests\Api\Urls;

class ChangeEmailTest extends AbstractTest
{
    public function testChangeEmailSuccess()
    {
        $this->login();

        $this->put(
            Urls::PROFILE_CHANGE_EMAIL_URL,
            [
                'currentPassword' => FixturesCommand::PASSWORD,
                'email' => FixturesCommand::EMAIL,
            ],
            204
        );
    }

    public function testChangeEmailFailedCurrentPassword()
    {
        $this->login();

        $res = $this->put(
            Urls::PROFILE_CHANGE_EMAIL_URL,
            [
                'currentPassword' => '__',
                'email' => FixturesCommand::EMAIL,
            ],
            422
        );

        $this->assertValidationFailedResponse($res, ['currentPassword']);
    }

    public function testChangeEmailFailedEmailAlreadyUsed()
    {
        $this->login();

        $email = 'onemoreemail@email.com';
        self::$container->get(EntitiesCreator::class)->createUser(
            'niiiick',
            $email
        );
        self::flush();

        $res = $this->put(
            Urls::PROFILE_CHANGE_EMAIL_URL,
            [
                'currentPassword' => FixturesCommand::PASSWORD,
                'email' => $email,
            ],
            422
        );

        $this->assertValidationFailedResponse($res, ['email']);
    }

    public function testChangeEmailFailedNotLogged()
    {
        $this->put(
            Urls::PROFILE_CHANGE_EMAIL_URL,
            [
                'currentPassword' => FixturesCommand::PASSWORD,
                'email' => FixturesCommand::EMAIL,
            ],
            401
        );
    }
}

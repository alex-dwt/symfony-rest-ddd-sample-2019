<?php

namespace App\Tests\Api\Profile;

use App\Application\Command\FixturesCommand;
use App\Tests\Api\AbstractTest;
use App\Tests\Api\Urls;

class ChangePasswordTest extends AbstractTest
{
    public function testChangePasswordSuccess()
    {
        $this->login();

        $this->put(
            Urls::PROFILE_CHANGE_PASSWORD_URL,
            [
                'currentPassword' => FixturesCommand::PASSWORD,
                'newPassword' => 'newPasswordnewPassword',
            ],
            204
        );
    }

    public function testChangePasswordFailed()
    {
        $this->login();

        $res = $this->put(
            Urls::PROFILE_CHANGE_PASSWORD_URL,
            [
                'currentPassword' => '___',
                'newPassword' => FixturesCommand::PASSWORD,
            ],
            422
        );

        $this->assertValidationFailedResponse($res, ['currentPassword']);
    }

    public function testChangePasswordFailedNotLogged()
    {
        $this->put(
            Urls::PROFILE_CHANGE_PASSWORD_URL,
            [
                'currentPassword' => FixturesCommand::PASSWORD,
                'newPassword' => FixturesCommand::PASSWORD,
            ],
            401
        );
    }
}

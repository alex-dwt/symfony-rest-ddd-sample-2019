<?php

namespace App\Tests\Api\Profile;

use App\Tests\Api\AbstractTest;
use App\Tests\Api\ResponseBodies;
use App\Tests\Api\Urls;

class RegistrationTest extends AbstractTest
{
    private const BODY = [
        'nickname' => 'nickname',
        'email' => 'email@email.com',
        'password' => 'password',
        'referer' => '',
        'language' => 'ru',
        'timezone' => '0',
    ];

    public function testRegistrationFailedRefererNotFound()
    {
        $res = $this->post(
            Urls::PROFILE_URL,
            ['referer' => 'fakefake'] + self::BODY,
            422
        );

        $this->assertValidationFailedResponse($res, ['referer']);
    }

    public function testRegistrationSuccess()
    {
        $res = $this->post(Urls::PROFILE_URL, self::BODY);

        $this->assertArray(
            $res,
            ResponseBodies::PROFILE_REGISTRATION_RESPONSE
        );
    }

    public function testRegistrationFailedNickUsed()
    {
        $res = $this->post(
            Urls::PROFILE_URL,
            ['email' => 'lal@lal.com'] + self::BODY,
            422
        );

        $this->assertValidationFailedResponse($res, ['nickname']);
    }

    public function testRegistrationFailedEmailUsed()
    {
        $res = $this->post(
            Urls::PROFILE_URL,
            ['nickname' => 'fakefake'] + self::BODY,
            422
        );

        $this->assertValidationFailedResponse($res, ['email']);
    }
}

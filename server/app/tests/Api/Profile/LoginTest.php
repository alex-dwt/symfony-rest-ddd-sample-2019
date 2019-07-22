<?php

namespace App\Tests\Api\Profile;

use App\Application\Command\FixturesCommand;
use App\Tests\Api\AbstractTest;
use App\Tests\Api\ResponseBodies;
use App\Tests\Api\Urls;

class LoginTest extends AbstractTest
{
    public function testLoginSuccess()
    {
        $res = $this->post(
            Urls::PROFILE_LOGIN_URL,
            [
                'nickname' => FixturesCommand::NICKNAME,
                'password' => FixturesCommand::PASSWORD,
            ],
            200
        );

        $this->assertArray(
            $res,
            ResponseBodies::PROFILE_REGISTRATION_RESPONSE
        );

        return $res['refresh_token'];
    }

    /**
     * @depends testLoginSuccess
     */
    public function testRefreshTokenSuccess(string $refreshToken)
    {
        $res = $this->post(
            Urls::PROFILE_RTOKEN_URL,
            [
                'refresh_token' => $refreshToken,
            ],
            200
        );

        $this->assertArray(
            $res,
            ResponseBodies::PROFILE_REGISTRATION_RESPONSE
        );
    }

    public function testLoginFailed()
    {
        $this->post(
            Urls::PROFILE_LOGIN_URL,
            [
                'nickname' => FixturesCommand::NICKNAME,
                'password' => '______',
            ],
            401
        );
    }

    public function testRefreshTokenFailed()
    {
        $this->post(
            Urls::PROFILE_RTOKEN_URL,
            [
                'refresh_token' => '___',
            ],
            401
        );
    }
}

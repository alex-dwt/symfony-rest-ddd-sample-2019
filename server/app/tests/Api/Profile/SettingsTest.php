<?php

namespace App\Tests\Api\Profile;

use App\Tests\Api\AbstractTest;
use App\Tests\Api\ResponseBodies;
use App\Tests\Api\Urls;

class SettingsTest extends AbstractTest
{
    private const BODY = [
        'language' => 'ru',
        'timezone' => '+12',
    ];

    public function testSetSettingsSuccess()
    {
        $this->login();

        $res = $this->put(Urls::PROFILE_SETTINGS_URL, self::BODY);

        $this->assertArray(
            $res,
            ResponseBodies::PROFILE_SETTINGS_RESPONSE
        );
    }

    public function testSetSettingsFailureNotLogged()
    {
        $this->put(Urls::PROFILE_SETTINGS_URL, self::BODY, 401);
    }

    public function testGetSettingsSuccess()
    {
        $this->login();

        $res = $this->get(Urls::PROFILE_SETTINGS_URL);

        $this->assertArray(
            $res,
            ResponseBodies::PROFILE_SETTINGS_RESPONSE
        );
    }

    public function testGetSettingsFailureNotLogged()
    {
        $this->get(Urls::PROFILE_SETTINGS_URL, 401);
    }
}

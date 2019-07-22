<?php

namespace App\Tests\Api\Profile;

use App\Tests\Api\AbstractTest;
use App\Tests\Api\ResponseBodies;
use App\Tests\Api\Urls;

class ProfileTest extends AbstractTest
{
    public function testViewMyProfileSuccess()
    {
        $this->login();

        $res = $this->get(Urls::PROFILE_URL);

        $this->assertArray(
            $res,
            ResponseBodies::PROFILE_VIEW_RESPONSE
        );
    }

    public function testViewMyProfileFailed()
    {
        $this->get(Urls::PROFILE_URL, 401);
    }
}

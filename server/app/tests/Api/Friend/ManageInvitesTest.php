<?php

namespace App\Tests\Api\Friend;

use App\Application\Command\FixturesCommand;
use App\Application\Service\Helper\EntitiesCreator;
use App\Domain\User\User;
use App\Tests\Api\AbstractTest;
use App\Tests\Api\ResponseBodies;
use App\Tests\Api\Urls;

class ManageInvitesTest extends AbstractTest
{
    private const USER_1_NICKNAME = 'user1';
    private const USER_2_NICKNAME = 'user2';

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $friendUser = self::$container->get(EntitiesCreator::class)->createUser(
            self::USER_1_NICKNAME,
            self::USER_1_NICKNAME . '@example.com'
        );

        self::$container->get(EntitiesCreator::class)->createUser(
            self::USER_2_NICKNAME,
            self::USER_2_NICKNAME . '@example.com'
        );

        self::flush();

        self::makeFriendship($friendUser, self::$initialUser);
    }

    public function testAcceptInvite()
    {
        $this->login();

        $this->put(Urls::FRIEND_INVITES_ACCEPT_URL, [], 204);
    }

    public function testCancelInvite()
    {
        $this->login();

        $this->put(Urls::FRIEND_INVITES_CANCEL_URL, [], 204);
    }

    public function testCreateInviteFailedTheSameUser()
    {
        $this->login();

        $this->post(Urls::FRIEND_INVITES_CREATE_URL, ['nickname' => FixturesCommand::NICKNAME], 450);
    }

    public function testCreateInviteFailedAlreadyFriend()
    {
        $this->login();

        $this->post(Urls::FRIEND_INVITES_CREATE_URL, ['nickname' => self::USER_1_NICKNAME], 451);
    }

    public function testCreateInviteFailedNotFound()
    {
        $this->login();

        $res = $this->post(Urls::FRIEND_INVITES_CREATE_URL, ['nickname' => 'fake'], 422);

        $this->assertValidationFailedResponse($res, ['nickname']);
    }

    public function testCreateInviteSuccess()
    {
        $this->login();

        $this->post(Urls::FRIEND_INVITES_CREATE_URL, ['nickname' => self::USER_2_NICKNAME], 204);
    }
}

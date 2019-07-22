<?php

namespace App\Tests\Api\Friend;

use App\Application\Command\FixturesCommand;
use App\Application\Service\Helper\EntitiesCreator;
use App\Domain\User\User;
use App\Tests\Api\AbstractTest;
use App\Tests\Api\ResponseBodies;
use App\Tests\Api\Urls;

class FriendsTest extends AbstractTest
{
    /**
     * @var User
     */
    private static $friendUser;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$friendUser = self::$container->get(EntitiesCreator::class)->createUser(
            'friend',
            'friend@example.com'
        );

        self::flush();

        self::makeFriendship(self::$friendUser, self::$initialUser);
    }

    public function testViewFriendsListSuccess()
    {
        $this->login();

        $this->paginatedGet(Urls::FRIEND_VIEW_LIST_URL);
    }

    public function testViewOneFriendNotFound()
    {
        $this->login();

        $this->get(Urls::FRIEND_VIEW_ONE_URL, 404);
    }

    public function testViewOneFriendSuccess()
    {
        $url = str_replace('_id', self::$friendUser->getId(), Urls::FRIEND_VIEW_ONE_URL);

        $this->login();

        $res = $this->get($url, 200);

        $this->assertArray($res, ResponseBodies::FRIEND_RESPONSE);
    }

    public function testDeleteFriend()
    {
        $this->login();

        $this->delete(Urls::FRIEND_VIEW_ONE_URL, [], 204);
    }
}

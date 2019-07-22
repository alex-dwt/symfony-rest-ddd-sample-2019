<?php

namespace App\Tests\Api\Friend;

use App\Application\Command\FixturesCommand;
use App\Tests\Api\AbstractTest;
use App\Tests\Api\ResponseBodies;
use App\Tests\Api\Urls;

class OutgoingAndIncomingInvitesTest extends AbstractTest
{
    public function testViewIncomingInvitesSuccess()
    {
        $this->login();

        $this->paginatedGet(Urls::FRIEND_INVITES_VIEW_INCOMING_LIST_URL);
    }

    public function testViewIncomingInvitesFailed()
    {
        $this->get(Urls::FRIEND_INVITES_VIEW_INCOMING_LIST_URL, 401);
    }

    public function testViewOutgoingInvitesSuccess()
    {
        $this->login();

        $this->paginatedGet(Urls::FRIEND_INVITES_VIEW_OUTGOING_LIST_URL);
    }

    public function testViewOutgoingInvitesFailed()
    {
        $this->get(Urls::FRIEND_INVITES_VIEW_OUTGOING_LIST_URL, 401);
    }
}

<?php

namespace App\Domain\User;

interface UserRepository
{
    public function getUserFriendsCount(User $user): int;
}

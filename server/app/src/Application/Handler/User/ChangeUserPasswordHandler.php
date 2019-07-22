<?php

namespace App\Application\Handler\User;

use App\Application\Handler\User\Exception\WrongCurrentUserPassword;
use App\Application\Request\User\ChangeUserPasswordRequest;
use App\Domain\User\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ChangeUserPasswordHandler
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder
    ){
        $this->passwordEncoder = $passwordEncoder;
    }

    public function __invoke(User $user, ChangeUserPasswordRequest $request)
    {
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $request->getNewPassword()
            )
        );
    }
}
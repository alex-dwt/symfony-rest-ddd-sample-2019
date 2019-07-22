<?php

namespace App\Application\Handler\User;

use App\Application\Request\User\RegisterUserRequest;
use App\Domain\User\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterUserHandler
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

    public function __invoke(RegisterUserRequest $request): User
    {
        // todo save referer

        $user = new User(
            $request->getNickname(),
            $request->getEmail(),
            $request->getLanguage(),
            $request->getTimezone()
        );

        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $request->getPassword()
            )
        );

        return $user;
    }
}
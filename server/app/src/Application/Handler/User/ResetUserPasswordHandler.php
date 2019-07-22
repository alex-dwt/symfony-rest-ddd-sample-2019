<?php

namespace App\Application\Handler\User;

use App\Domain\User\Criteria\UserByRestorePasswordLinkCriteria;
use App\Domain\User\User;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetUserPasswordHandler
{
    /**
     * @var DoctrineUserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(
        DoctrineUserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ){
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function __invoke(User $user, string $userResetPasswordLinkHash): bool
    {
        /** @var User $result */
        if (!$result = $this->userRepository->getOneByCriteria(
                new UserByRestorePasswordLinkCriteria($userResetPasswordLinkHash)
        )) {
            return false;
        }

        if ($result !== $user) {
            return false;
        }

        $plainPassword = substr(
            str_shuffle(implode('', array_merge(range(1, 9), range('a', 'z')))),
            0,
            10
        );
        $hashedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);

        return $user->resetPasswordUsingRestoreLink(
            $hashedPassword,
            $plainPassword
        );
    }
}
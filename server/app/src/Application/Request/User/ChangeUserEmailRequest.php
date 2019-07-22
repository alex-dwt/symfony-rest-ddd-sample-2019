<?php

namespace App\Application\Request\User;

use App\Application\Request\GeneralRequest;
use App\Domain\User\Criteria\UserByEmailCriteria;
use App\Domain\User\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method string getCurrentPassword()
 * @method string getEmail()
 */
class ChangeUserEmailRequest extends GeneralRequest
{
    /**
     * @var DoctrineUserRepository
     */
    private $userRepository;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(
        ValidatorInterface $validator,
        DoctrineUserRepository $userRepository,
        TokenStorageInterface $tokenStorage,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->userRepository = $userRepository;
        $this->tokenStorage = $tokenStorage;
        $this->passwordEncoder = $passwordEncoder;

        parent::__construct($validator);
    }

    protected function getValidationRules(): array
    {
        return parent::getValidationRules() + [
            'currentPassword' => self::getCurrentPasswordRules(
                $this->tokenStorage,
                $this->passwordEncoder
            ),
            'email' => [
                function (&$val) {
                    $val = trim((string) $val);
                },
                new Assert\NotBlank(),
                new Assert\Length(['max' => self::MAX_LENGTH]),
                new Assert\Email(),
                new Assert\Callback(function ($val, ExecutionContextInterface $context) {
                    if ($val !== ''
                        && ($user = $this->userRepository->getOneByCriteria(new UserByEmailCriteria($val)))
                    ) {
                        if (!$this->tokenStorage->getToken()
                            || !$this->tokenStorage->getToken()->getUser()
                            || $this->tokenStorage->getToken()->getUser() !== $user
                        ) {
                            $context
                                ->buildViolation('Email is already used!')
                                ->addViolation();
                        }
                    }
                }),
            ],
        ];
    }
}

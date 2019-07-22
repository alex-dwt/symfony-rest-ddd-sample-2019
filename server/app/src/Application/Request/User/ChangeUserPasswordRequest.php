<?php

namespace App\Application\Request\User;

use App\Application\Request\GeneralRequest;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @method string getCurrentPassword()
 * @method string getNewPassword()
 */
class ChangeUserPasswordRequest extends GeneralRequest
{
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
        TokenStorageInterface $tokenStorage,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
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
            'newPassword' => self::getPasswordRules(),
        ];
    }
}

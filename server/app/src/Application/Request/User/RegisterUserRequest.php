<?php

namespace App\Application\Request\User;

use App\Application\Request\GeneralRequest;
use App\Domain\User\Criteria\UserByEmailCriteria;
use App\Domain\User\Criteria\UserByNicknameCriteria;
use App\Domain\User\UserSettings;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method string getNickname()
 * @method string getEmail()
 * @method string getPassword()
 * @method string getReferer()
 * @method string getLanguage()
 * @method string getTimezone()
 */
class RegisterUserRequest extends GeneralRequest
{
    /**
     * @var DoctrineUserRepository
     */
    private $userRepository;

    public function __construct(
        ValidatorInterface $validator,
        DoctrineUserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;

        parent::__construct($validator);
    }

    protected function getValidationRules(): array
    {
        return parent::getValidationRules() + [
            'nickname' => array_merge(
                self::getNicknameRules(),
                [
                    new Assert\Callback(function ($val, ExecutionContextInterface $context) {
                        if ($val !== ''
                            && $this->userRepository->getOneByCriteria(new UserByNicknameCriteria($val))
                        ) {
                            $context
                                ->buildViolation('Nickname is already used!')
                                ->addViolation();
                        }
                    }),
                ]
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
                        && $this->userRepository->getOneByCriteria(new UserByEmailCriteria($val))
                    ) {
                        $context
                            ->buildViolation('Email is already used!')
                            ->addViolation();
                    }
                }),
            ],
            'referer' => [
                function (&$val) {
                    $val = trim((string) $val);
                },
                new Assert\Length(['max' => self::MAX_LENGTH]),
                new Assert\Callback(function ($val, ExecutionContextInterface $context) {
                    if ($val !== ''
                        && !$this->userRepository->getOneByCriteria(new UserByNicknameCriteria($val))
                    ) {
                        $context
                            ->buildViolation('Referer is not found!')
                            ->addViolation();
                    }
                }),
            ],
            'language' => self::getLanguageRules(),
            'timezone' => self::getTimezoneRules(),
            'password' => self::getPasswordRules(),
        ];
    }
}

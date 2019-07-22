<?php

namespace App\Application\Request\Friend;

use App\Application\Request\GeneralRequest;
use App\Domain\User\Criteria\UserByEmailCriteria;
use App\Domain\User\Criteria\UserByNicknameCriteria;
use App\Domain\User\User;
use App\Domain\User\UserSettings;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CreateFriendInviteRequest extends GeneralRequest
{
    /**
     * @var DoctrineUserRepository
     */
    private $userRepository;

    /**
     * @var User
     */
    private $user;

    public function __construct(
        ValidatorInterface $validator,
        DoctrineUserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;

        parent::__construct($validator);
    }

    public function getUser(): User
    {
        if (!$this->user) {
            throw new \LogicException('User should be set');
        }

        return $this->user;
    }

    protected function getValidationRules(): array
    {
        return parent::getValidationRules() + [
            'nickname' => array_merge(
                self::getNicknameRules(),
                [
                    new Assert\Callback(function ($val, ExecutionContextInterface $context) {
                        if ($val !== ''
                            && !$this->user = $this->userRepository->getOneByCriteria(new UserByNicknameCriteria($val))
                        ) {
                            $context
                                ->buildViolation('Nickname is not found!')
                                ->addViolation();
                        }
                    }),
                ]
            ),
        ];
    }
}

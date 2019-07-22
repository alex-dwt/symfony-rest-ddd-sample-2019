<?php

namespace App\Application\Request;

use AlexDwt\VerifiedRequestBundle\Request\VerifiedRequest;
use App\Application\Request\User\RegisterUserRequest;
use App\Domain\User\Criteria\UserByEmailCriteria;
use App\Domain\User\User;
use App\Domain\User\UserSettings;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method string getAppLanguage()
 * @method string getAppTimezone()
 */
class GeneralRequest extends VerifiedRequest
{
    const MAX_LENGTH = 200;

    protected function getValidationRules(): array
    {
        return [
            'appLanguage' => self::getLanguageRules(),
            'appTimezone' => self::getTimezoneRules(),
        ];
    }

    protected static function getCurrentPasswordRules(
        TokenStorageInterface $tokenStorage,
        UserPasswordEncoderInterface $passwordEncoder
    ): array {
        return array_merge(
            self::getPasswordRules(),
            [
                new Assert\Callback(function ($val, ExecutionContextInterface $context) use ($tokenStorage, $passwordEncoder) {
                    /** @var User $user */
                    if (!$tokenStorage->getToken()
                        || !($user = $tokenStorage->getToken()->getUser())
                        || !$passwordEncoder->isPasswordValid($user, $val)
                    ) {
                        $context
                            ->buildViolation('User\'s current password is wrong')
                            ->addViolation();
                    }
                }),
            ]
        );
    }

    protected static function getPasswordRules(): array
    {
        return [
            function (&$val) {
                $val = trim((string) $val);
            },
            new Assert\NotBlank(),
            new Assert\Length(['max' => self::MAX_LENGTH]),
        ];
    }

    protected static function getTimezoneRules(): array
    {
        return [
            function (&$val) {
                $val = trim((string) $val);
            },
            new Assert\NotBlank(),
            new Assert\Choice(['callback' => function() {
                $res = ['0'];
                foreach (range(1, 12) as $value) {
                    $res[] = '+' . $value;
                    $res[] = '-' . $value;
                }

                return $res;
            }]),
        ];
    }

    public static function getLanguageRules(): array
    {
        return [
            function (&$val) {
                $val = trim((string) $val);
            },
            new Assert\NotBlank(),
            new Assert\Choice(UserSettings::LANGUAGES),
        ];
    }

    public static function getNicknameRules(): array
    {
        return [
            function (&$val) {
                $val = trim((string) $val);
            },
            new Assert\NotBlank(),
            new Assert\Length(['max' => self::MAX_LENGTH]),
            new Assert\Regex(['pattern' => '/^[\.\-_a-z\d]+$/i']),
        ];
    }

    public static function getIdRules(bool $addClosure = true): array
    {
        $res = [];

        if ($addClosure) {
            $res[] = function (&$val) {
                $val = trim((string) $val);
            };
        }

        $res[] = new Assert\NotBlank();
        $res[] = new Assert\Length(['max' => 100]);

        return $res;
    }
}

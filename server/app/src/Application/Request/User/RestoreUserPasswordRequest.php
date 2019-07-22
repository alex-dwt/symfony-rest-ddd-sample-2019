<?php

namespace App\Application\Request\User;

use App\Application\Request\GeneralRequest;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @method string getIdentity()
 */
class RestoreUserPasswordRequest extends GeneralRequest
{
    protected function getValidationRules(): array
    {
        return parent::getValidationRules() + [
            'identity' => [
                function (&$val) {
                    $val = trim((string) $val);
                },
                new Assert\NotBlank(),
                new Assert\Length(['max' => self::MAX_LENGTH]),
            ],
        ];
    }
}

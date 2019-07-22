<?php

namespace App\Application\Request\User;

use App\Application\Request\GeneralRequest;

/**
 * @method string getLanguage()
 * @method string getTimezone()
 */
class UpdateUserSettingsRequest extends GeneralRequest
{
    protected function getValidationRules(): array
    {
        return parent::getValidationRules() + [
            'language' => self::getLanguageRules(),
            'timezone' => self::getTimezoneRules(),
        ];
    }

    protected function getOptionalFields(): array
    {
        return [
            'language' => null,
            'timezone' => null,
        ];
    }
}

<?php

namespace App\Application\Request;

/**
 * @method int getLimit()
 * @method int getOffset()
 */
class PaginationRequest extends GeneralRequest
{
    private const MAX_LIMIT = 40;

    protected function getValidationRules(): array
    {
        return parent::getValidationRules() + [
            'limit' => [
                function (&$val) {
                    $val = max((int) $val, 0);

                    if (!$val || $val > self::MAX_LIMIT) {
                        $val = self::MAX_LIMIT;
                    }
                },
            ],
            'offset' => [
                function (&$val) {
                    $val = max((int) $val, 0);
                },
            ],
        ];
    }
}

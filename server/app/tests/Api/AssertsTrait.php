<?php

namespace App\Tests\Api;

trait AssertsTrait
{
    protected function assertFieldExists(
        array $responseArray,
        string $fieldName
    ) {
        $val = $responseArray;

        foreach (explode('.', $fieldName) as $part) {
            $this->assertTrue(
                isset($val[$part]),
                'Field "'. $fieldName . '" is not found'
            );
            $val = $val[$part];
        }
    }

    protected function assertArray(
        array $responseArray,
        array $fields,
        string $fieldPrefix = ''
    ) {
        $this->assertSame(
            count($fields),
            count($responseArray),
            "Count of fields is not equal (prefix is \"$fieldPrefix\")\r\n" . print_r($responseArray, true)
        );

        foreach ($fields as $name => $val) {
            $fieldFullName = ($fieldPrefix ? "$fieldPrefix." : '') . $name;

            $this->assertTrue(
                isset($responseArray[$name]),
                'Field "'. $fieldFullName . '" is not found'
            );

            if (is_array($val)) {
                $this->assertArray(
                    (array) $responseArray[$name],
                    $val,
                    $name
                );
            } else {
                $this->assertInternalType(
                    $val,
                    $responseArray[$name],
                    'Type of field "' . $fieldFullName . '" is wrong'
                );
            }
        }
    }

    protected function assertValidationFailedResponse(array $response, array $errors)
    {
        $this->assertArray($response, [
            'message' => 'string',
            'errors' => array_fill_keys($errors, 'array'),
            'code' => 'int',
        ]);
    }
}
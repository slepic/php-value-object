<?php

namespace Slepic\ValueObject;

final class TypeError
{
    private static array $hints = [
        'must be an instance of',
        'must be of the type',
        'must be an',
        'must be a',
        'must be'
    ];

    private const SYMBOL_PATTERN = '([a-zA-Z0-9_\\\\]+)';

    private function __construct()
    {
    }

    public static function getExpectedType(\TypeError $error): ?string
    {
        $errorMessage = $error->getMessage();
        foreach (self::$hints as $hint) {
            $matches = [];
            if (\preg_match('/' . $hint . ' ' . self::SYMBOL_PATTERN . '/', $errorMessage, $matches) === 1) {
                if (isset($matches[1])) {
                    return $matches[1];
                }
            }
        }
        return null;
    }
}

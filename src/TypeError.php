<?php

namespace Slepic\ValueObject;

/**
 * Helper class to retrieve name of expected type from a \TypeError instance.
 */
final class TypeError
{
    /**
     * Array of substrings that potentially precede type name in a TypeError message,
     * ordered in order of no collision.
     *
     * That is if hint A is before hint B, then hint A cannot be a substring of hint B.
     *
     * @var array<string>
     */
    private static array $hints = [
        'must be an instance of',
        'must be of the type',
        'must be an',
        'must be a',
        'must be'
    ];

    /**
     * Pattern for class or built-in type name
     */
    private const SYMBOL_PATTERN = '([a-zA-Z0-9_\\\\]+)';

    /**
     * Static class cannot be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * Get the string representation of the type that was expected but not received.
     *
     * @param \TypeError $error
     * @return string|null Returns same value as by \gettype() for built-in types or a class name for objects.
     *      Returns null if type could not be detected (@todo should this be impossible?).
     */
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

<?php

namespace Slepic\ValueObject\Strings;

trait StringMaxLengthTrait
{
    protected static int $maxLength;

    protected static function createValueTooLongException(int $length, string $value): StringTooLongExceptionInterface
    {
        return new StringTooLongException(static::$maxLength, $length, $value);
    }

    /**
     * @param int $length
     * @param string $value
     * @throws StringTooLongExceptionInterface
     */
    final protected function checkMaxLength(int $length, string $value): void
    {
        if ($length > self::$maxLength) {
            throw static::createValueTooLongException($length, $value);
        }
    }
}

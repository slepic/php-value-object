<?php

namespace Slepic\ValueObject\Strings;

trait StringMinLengthTrait
{
    protected static int $minLength;

    protected static function createValueTooShortException(int $length, string $value): StringTooShortExceptionInterface
    {
        return new StringTooShortException(static::$minLength, $length, $value);
    }

    /**
     * @param int $length
     * @param string $value
     * @throws StringTooShortExceptionInterface
     */
    final protected function checkMinLength(int $length, string $value): void
    {
        if ($length < self::$minLength) {
            throw static::createValueTooShortException($length, $value);
        }
    }
}

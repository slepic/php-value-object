<?php declare(strict_types=1);

namespace Slepic\ValueObject;

class InvalidTypeException extends InvalidValueException implements InvalidTypeExceptionInterface
{
    /**
     * @param mixed $value
     * @param string|null $expectation
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        $value,
        ?string $expectation,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        if (!$message) {
            $type = \is_object($value) ? \get_class($value) : \gettype($value);
            if ($expectation !== null) {
                $message = "Expected \"$expectation\", got \"$type\".";
            } else {
                $message = "\"$type\" not expected.";
            }
        }
        parent::__construct($value, $expectation, $message, $code, $previous);
    }
}

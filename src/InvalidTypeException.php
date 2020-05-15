<?php declare(strict_types=1);

namespace Slepic\ValueObject;

class InvalidTypeException extends InvalidValueException implements InvalidTypeExceptionInterface
{
    private TypeExpectationInterface $expectation;

    /**
     * @param TypeExpectationInterface $expectation
     * @param mixed $value
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        TypeExpectationInterface $expectation,
        $value,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->expectation = $expectation;
        if (!$message) {
            $type = \is_object($value) ? \get_class($value) : \gettype($value);
            $message = "\"$type\" not expected.";
        }
        parent::__construct($value, $message, $code, $previous);
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->expectation;
    }
}

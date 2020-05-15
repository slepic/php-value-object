<?php declare(strict_types=1);

namespace Slepic\ValueObject;

class InvalidValueException extends \InvalidArgumentException implements InvalidValueExceptionInterface
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param mixed $value
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        $value,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        if (!$message) {
            if (\is_object($value) && !\method_exists($value, '__toString')) {
                $serializedValue = 'object';
            } else {
                $serializedValue = (string) $value;
            }
            $message = "\"$serializedValue\" not expected.";
        }
        $this->value = $value;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}

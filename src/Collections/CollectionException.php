<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\InvalidValueException;

class CollectionException extends InvalidValueException implements CollectionExceptionInterface
{
    private array $errors;

    public function __construct(
        array $errors,
        array $value,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->errors = $errors;
        parent::__construct($value, $message, $code, $previous);
    }

    public function getValue(): array
    {
        return parent::getValue();
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}

<?php declare(strict_types=1);

namespace Slepic\ValueObject\Enum;

use Slepic\ValueObject\Strings\StringException;

class StringEnumException extends StringException implements StringEnumExceptionInterface
{
    /**
     * @var array<string>
     */
    private array $allowedValues;

    /**
     * @param array<string> $allowedValues
     * @param string $value
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        array $allowedValues,
        string $value,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->allowedValues = $allowedValues;
        parent::__construct($value, $message, $code, $previous);
    }

    public function getAllowedValues(): array
    {
        return $this->allowedValues;
    }
}

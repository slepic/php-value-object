<?php declare(strict_types=1);

namespace Slepic\ValueObject\Enum;

use Slepic\ValueObject\Strings\StringViolation;
use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

final class StringEnumViolation extends StringViolation
{
    /**
     * @var array<string>
     */
    private array $allowedValues;

    /**
     * @param array<string> $allowedValues
     * @param string $message
     */
    public function __construct(array $allowedValues, string $message = '')
    {
        $this->allowedValues = $allowedValues;
        parent::__construct($message ?: ('Expected one of: ' . \implode(', ', $allowedValues)));
    }

    public function getAllowedValues(): array
    {
        return $this->allowedValues;
    }

    public static function exception(array $allowedValues): ViolationExceptionInterface
    {
        return new ViolationException([new self($allowedValues)]);
    }
}

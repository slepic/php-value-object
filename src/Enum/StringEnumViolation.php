<?php declare(strict_types=1);

namespace Slepic\ValueObject\Enum;

use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;
use Slepic\ValueObject\ViolationInterface;

final class StringEnumViolation implements ViolationInterface
{
    /**
     * @var array<string>
     */
    private array $allowedValues;

    /**
     * @param array<string> $allowedValues
     */
    public function __construct(array $allowedValues)
    {
        $this->allowedValues = $allowedValues;
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

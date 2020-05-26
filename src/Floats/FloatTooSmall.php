<?php declare(strict_types=1);

namespace Slepic\ValueObject\Floats;

use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

final class FloatTooSmall extends FloatViolation
{
    private float $lowerBound;

    public function __construct(float $lowerBound, string $message = '')
    {
        $this->lowerBound = $lowerBound;
        parent::__construct($message ?: "Expected value no smaller then $lowerBound.");
    }

    public function getLowerBound(): float
    {
        return $this->lowerBound;
    }

    public static function exception(float $lowerBound): ViolationExceptionInterface
    {
        return ViolationException::for(new self($lowerBound));
    }
}

<?php declare(strict_types=1);

namespace Slepic\ValueObject\Floats;

use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

final class FloatTooBig implements FloatViolationInterface
{
    private float $upperBound;

    public function __construct(float $upperBound)
    {
        $this->upperBound = $upperBound;
    }

    public function getUpperBound(): float
    {
        return $this->upperBound;
    }

    public static function exception(float $upperBound): ViolationExceptionInterface
    {
        return new ViolationException([new self($upperBound)]);
    }
}

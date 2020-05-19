<?php declare(strict_types=1);

namespace Slepic\ValueObject\Floats;

use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;
use Slepic\ValueObject\ViolationInterface;

final class FloatTooSmall implements ViolationInterface
{
    private float $lowerBound;

    public function __construct(float $lowerBound)
    {
        $this->lowerBound = $lowerBound;
    }

    public function getLowerBound(): float
    {
        return $this->lowerBound;
    }

    public static function exception(float $lowerBound): ViolationExceptionInterface
    {
        return new ViolationException([new self($lowerBound)]);
    }
}

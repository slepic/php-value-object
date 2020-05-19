<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;
use Slepic\ValueObject\ViolationInterface;

final class IntegerTooBig implements ViolationInterface
{
    private int $upperBound;

    public function __construct(int $upperBound)
    {
        $this->upperBound = $upperBound;
    }

    public function getUpperBound(): int
    {
        return $this->upperBound;
    }

    public static function exception(int $upperBound): ViolationExceptionInterface
    {
        return new ViolationException([new self($upperBound)]);
    }
}

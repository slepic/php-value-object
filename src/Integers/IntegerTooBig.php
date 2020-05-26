<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

final class IntegerTooBig extends IntegerViolation
{
    private int $upperBound;

    public function __construct(int $upperBound, string $message = '')
    {
        $this->upperBound = $upperBound;
        parent::__construct($message ?: "Expected integer no greater then $upperBound.");
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

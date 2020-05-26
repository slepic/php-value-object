<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

final class IntegerTooSmall extends IntegerViolation
{
    private int $lowerBound;

    public function __construct(int $lowerBound, string $message = '')
    {
        $this->lowerBound = $lowerBound;
        parent::__construct($message ?: "Expected integer no smaller then $lowerBound.");
    }

    public function getLowerBound(): int
    {
        return $this->lowerBound;
    }

    public static function exception(int $lowerBound): ViolationExceptionInterface
    {
        return new ViolationException([new self($lowerBound)]);
    }
}

<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

class EvenInteger extends IntegerValue
{
    public function __construct(int $value)
    {
        if ($value % 2 !== 0) {
            throw new IntegerException($value, 'Expected even integer.');
        }
        parent::__construct($value);
    }
}

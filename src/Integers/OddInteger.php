<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

class OddInteger extends IntegerValue
{
    public function __construct(int $value)
    {
        if ($value % 2 !== 1) {
            throw new IntegerException($value, 'odd', 'Expected odd integer.');
        }
        parent::__construct($value);
    }
}

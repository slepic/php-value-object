<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

/**
 * Unlike strlen which is O(1), mb_strlen is O(n).
 * This class can be used to store the length if computed in advance.
 */
abstract class MultiByteString extends StringValue
{
    private int $length;

    protected function __construct(string $value, int $length)
    {
        $this->length = $length;
        parent::__construct($value);
    }

    public function getLength(): int
    {
        return $this->length;
    }
}

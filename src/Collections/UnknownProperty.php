<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type;

/**
 * @deprecated use CollectionViolation
 */
final class UnknownProperty extends CollectionViolation
{
    /**
     * @param string $key
     * @param mixed $value
     * @param string $message
     */
    public function __construct(string $key, $value, string $message = '')
    {
        parent::__construct(
            $key,
            Type::forBuiltinType('void')->getExpectation(),
            [new Type\TypeViolation()],
            $value,
            $message ?: "Property \"$key\" wasn't expected."
        );
    }
}

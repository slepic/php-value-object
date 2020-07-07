<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type\TypeExpectationInterface;

/**
 * @deprecated use CollectionViolation with MissingValue
 */
final class MissingRequiredProperty extends CollectionViolation
{
    public function __construct(string $key, TypeExpectationInterface $expectation, string $message = '')
    {
        parent::__construct(
            $key,
            $expectation,
            [new MissingValue()],
            null,
            $message ?: "Missing required property \"$key\"."
        );
    }
}

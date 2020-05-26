<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type\TypeExpectationInterface;

final class MissingRequiredProperty extends PropertyViolation
{
    public function __construct(string $key, TypeExpectationInterface $expectation, string $message = '')
    {
        parent::__construct($key, $expectation, $message ?: "Missing required property \"$key\".");
    }
}

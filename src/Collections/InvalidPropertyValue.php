<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type\TypeExpectationInterface;
use Slepic\ValueObject\ViolationInterface;

/**
 * @deprecated use CollectionViolation
 */
final class InvalidPropertyValue extends CollectionViolation
{
    /**
     * @param string $key
     * @param TypeExpectationInterface $expectation
     * @param mixed $value
     * @param array<ViolationInterface> $violations
     * @param string $message
     */
    public function __construct(
        string $key,
        TypeExpectationInterface $expectation,
        $value,
        array $violations,
        string $message = ''
    ) {
        parent::__construct($key, $expectation, $violations, $value, $message);
    }
}

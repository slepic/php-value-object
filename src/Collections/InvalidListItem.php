<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type\TypeExpectationInterface;

/**
 * @deprecated use CollectionViolation
 */
final class InvalidListItem extends CollectionViolation
{

    /**
     * @param int $key
     * @param TypeExpectationInterface $expectation
     * @param mixed $value
     * @param array $violations
     * @param string $message
     */
    public function __construct(
        int $key,
        TypeExpectationInterface $expectation,
        $value,
        array $violations,
        string $message = ''
    ) {
        parent::__construct($key, $expectation, $violations, $value, $message ?: "Invalid item on index $key.");
    }
}

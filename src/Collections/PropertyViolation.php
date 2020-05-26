<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type\TypeExpectationInterface;
use Slepic\ValueObject\Violation;

class PropertyViolation extends Violation implements CollectionItemViolationInterface
{
    private string $key;
    private TypeExpectationInterface $expectation;

    public function __construct(string $key, TypeExpectationInterface $expectation, string $message = '')
    {
        $this->key = $key;
        $this->expectation = $expectation;
        parent::__construct($message ?: "Invalid property $key.");
    }

    final public function getKey(): string
    {
        return $this->key;
    }

    final public function getExpectation(): TypeExpectationInterface
    {
        return $this->expectation;
    }
}

<?php declare(strict_types=1);

namespace Slepic\ValueObject;

use Slepic\ValueObject\Type\TypeExpectationInterface;

final class Error implements ErrorInterface
{
    /**
     * @var TypeExpectationInterface
     */
    private TypeExpectationInterface $expectation;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var array
     */
    private array $violations;

    /**
     * @param TypeExpectationInterface $expectation
     * @param mixed $value
     * @param ViolationInterface[] $violations
     */
    public function __construct(TypeExpectationInterface $expectation, $value, ViolationInterface ...$violations)
    {
        $this->expectation = $expectation;
        $this->value = $value;
        $this->violations = $violations;
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->expectation;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getViolations(): array
    {
        return $this->violations;
    }
}

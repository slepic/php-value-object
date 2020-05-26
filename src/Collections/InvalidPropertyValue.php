<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type\TypeExpectationInterface;
use Slepic\ValueObject\ViolationInterface;

final class InvalidPropertyValue extends PropertyViolation
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var array<ViolationInterface>
     */
    private array $violations;

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
        $this->value = $value;
        $this->violations = $violations;
        parent::__construct($key, $expectation, $message);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array<ViolationInterface>
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}

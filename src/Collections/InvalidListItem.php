<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type\TypeExpectationInterface;
use Slepic\ValueObject\Violation;
use Slepic\ValueObject\ViolationInterface;

final class InvalidListItem extends Violation implements CollectionItemViolationInterface
{
    private int $key;
    private TypeExpectationInterface $expectation;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var array<ViolationInterface>
     */
    private array $violations;

    /**
     * @param int $key
     * @param TypeExpectationInterface $expectation
     * @param mixed $value
     * @param array<ViolationInterface> $violations
     * @param string $message
     */
    public function __construct(
        int $key,
        TypeExpectationInterface $expectation,
        $value,
        array $violations,
        string $message = ''
    ) {
        $this->key = $key;
        $this->expectation = $expectation;
        $this->value = $value;
        $this->violations = $violations;
        parent::__construct($message ?: "Invalid item on index $key.");
    }

    public function getKey(): int
    {
        return $this->key;
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->expectation;
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

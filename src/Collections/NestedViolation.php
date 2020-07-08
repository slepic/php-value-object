<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type;
use Slepic\ValueObject\Type\TypeExpectationInterface;
use Slepic\ValueObject\Violation;

/**
 * @psalm-template TKey
 * @template-implements NestedViolationInterface<TKey>
 */
class NestedViolation extends Violation implements NestedViolationInterface
{
    /**
     * @psalm-var TKey
     * @var mixed
     */
    private $key;

    /**
     * @var mixed
     */
    private $value;
    private TypeExpectationInterface $expectation;
    private array $violations;

    /**
     * @psalm-param TKey $key
     * @param mixed $key
     * @param TypeExpectationInterface $expectation
     * @param array $violations
     * @param mixed $value
     * @param string $message
     */
    public function __construct(
        $key,
        TypeExpectationInterface $expectation,
        array $violations,
        $value = null,
        string $message = ''
    ) {
        $this->key = $key;
        $this->expectation = $expectation;
        $this->violations = $violations;
        $this->value = $value;
        parent::__construct($message ?: 'The collection is invalid.');
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->expectation;
    }

    public function getViolations(): array
    {
        return $this->violations;
    }

    /**
     * @param int $key
     * @param TypeExpectationInterface $expectation
     * @param mixed $value
     * @param array $violations
     * @return self
     */
    public static function invalidItem(int $key, TypeExpectationInterface $expectation, $value, array $violations): self
    {
        return new self($key, $expectation, $violations, $value);
    }

    /**
     * @param string $key
     * @param TypeExpectationInterface $expectation
     * @param mixed $value
     * @param array $violations
     * @return self
     */
    public static function invalidProperty(
        string $key,
        TypeExpectationInterface $expectation,
        $value,
        array $violations
    ): self {
        return new self($key, $expectation, $violations, $value);
    }

    public static function missingRequiredProperty(string $key, TypeExpectationInterface $expectation): self
    {
        return new self($key, $expectation, [new MissingValue()]);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public static function unknownProperty(string $key, $value): self
    {
        return new self(
            $key,
            Type::forBuiltinType('void')->getExpectation(),
            [new Type\TypeViolation()],
            $value
        );
    }
}

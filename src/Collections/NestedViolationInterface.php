<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type\TypeExpectationInterface;
use Slepic\ValueObject\ViolationInterface;

/**
 * @psalm-template TKey
 */
interface NestedViolationInterface extends ViolationInterface
{
    /**
     * @psalm-return TKey
     * @return mixed
     */
    public function getKey();

    /**
     * @return mixed
     */
    public function getValue();

    public function getExpectation(): TypeExpectationInterface;

    /**
     * @return ViolationInterface[]
     */
    public function getViolations(): array;
}

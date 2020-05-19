<?php declare(strict_types=1);

namespace Slepic\ValueObject;

use Slepic\ValueObject\Type\TypeExpectationInterface;

interface ErrorInterface
{
    /**
     * @return TypeExpectationInterface
     */
    public function getExpectation(): TypeExpectationInterface;

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return array<ViolationInterface>
     */
    public function getViolations(): array;
}

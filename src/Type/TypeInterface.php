<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type;

use Slepic\ValueObject\ViolationExceptionInterface;

interface TypeInterface
{
    /**
     * @return TypeExpectationInterface
     */
    public function getExpectation(): TypeExpectationInterface;

    /**
     * @param mixed $value
     * @return mixed
     * @throws ViolationExceptionInterface
     */
    public function prepareValue($value);
}

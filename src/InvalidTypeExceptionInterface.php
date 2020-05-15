<?php declare(strict_types=1);

namespace Slepic\ValueObject;

interface InvalidTypeExceptionInterface extends InvalidValueExceptionInterface
{
    public function getExpectation(): TypeExpectationInterface;
}

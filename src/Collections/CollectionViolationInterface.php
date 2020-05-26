<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type\TypeExpectationInterface;
use Slepic\ValueObject\ViolationInterface;

/**
 * @psalm-template TKey
 */
interface CollectionViolationInterface extends ViolationInterface
{
    /**
     * @psalm-return TKey
     * @return mixed
     */
    public function getKey();

    public function getExpectation(): TypeExpectationInterface;
}

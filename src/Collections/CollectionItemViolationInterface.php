<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type\TypeExpectationInterface;
use Slepic\ValueObject\ViolationInterface;

/**
 * Represents violation of a collection when
 * a collection key is valid, but the item does not meet expectation.
 *
 * @psalm-template TKey
 */
interface CollectionItemViolationInterface extends ViolationInterface
{
    /**
     * @psalm-return TKey
     * @return mixed
     */
    public function getKey();

    public function getExpectation(): TypeExpectationInterface;
}

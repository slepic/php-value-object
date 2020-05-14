<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

/**
 * @psalm-template TKey
 * @psalm-template TValue
 */
interface KeyValuePairInterface
{
    /**
     * @psalm-return TKey
     *
     * @return mixed
     */
    public function getKey();

    /**
     * @psalm-return TValue
     *
     * @return mixed
     */
    public function getValue();
}

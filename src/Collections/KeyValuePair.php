<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

/**
 * @psalm-template TKey
 * @psalm-template TValue
 */
class KeyValuePair implements KeyValuePairInterface
{
    /**
     * @psalm-var TKey
     * @var mixed
     */
    private $key;

    /**
     * @psalm-var TValue
     * @var mixed
     */
    private $value;

    /**
     * @psalm-param TKey $key
     * @psalm-param TValue $value
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @psalm-return TKey
     *
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @psalm-return TValue
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}

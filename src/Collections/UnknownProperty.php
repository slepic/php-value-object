<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Violation;

final class UnknownProperty extends Violation
{
    private string $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param string $key
     * @param mixed $value
     * @param string $message
     */
    public function __construct(string $key, $value, string $message = '')
    {
        $this->key = $key;
        $this->value = $value;
        parent::__construct($message ?: "Property \"$key\" wasn't expected.");
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}

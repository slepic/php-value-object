<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\ErrorInterface;
use Slepic\ValueObject\ViolationInterface;

final class CollectionViolation implements ViolationInterface
{
    /**
     * @var mixed
     */
    private $key;

    private ErrorInterface $error;

    /**
     * @param mixed $key
     * @param ErrorInterface $error
     */
    public function __construct($key, ErrorInterface $error)
    {
        $this->key = $key;
        $this->error = $error;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    public function getError(): ErrorInterface
    {
        return $this->error;
    }
}

<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\ErrorInterface;
use Slepic\ValueObject\ViolationInterface;

class CollectionKeyViolation implements ViolationInterface
{
    private ErrorInterface $error;

    public function __construct(ErrorInterface $error)
    {
        $this->error = $error;
    }

    public function getError(): ErrorInterface
    {
        return $this->error;
    }
}

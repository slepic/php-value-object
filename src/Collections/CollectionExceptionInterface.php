<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\InvalidValueExceptionInterface;

interface CollectionExceptionInterface extends InvalidValueExceptionInterface
{
    public function getValue(): array;

    /**
     * @return array<InvalidValueExceptionInterface>
     */
    public function getErrors(): array;
}

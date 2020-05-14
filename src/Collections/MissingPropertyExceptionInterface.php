<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

interface MissingPropertyExceptionInterface extends CollectionExceptionInterface
{
    public function getMissingProperty(): string;
}

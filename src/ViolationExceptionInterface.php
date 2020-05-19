<?php declare(strict_types=1);

namespace Slepic\ValueObject;

interface ViolationExceptionInterface extends \Throwable
{
    /**
     * @return array<ViolationInterface>
     */
    public function getViolations(): array;
}

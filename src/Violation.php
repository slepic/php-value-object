<?php declare(strict_types=1);

namespace Slepic\ValueObject;

class Violation implements ViolationInterface
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    final public function getMessage(): string
    {
        return $this->message;
    }
}

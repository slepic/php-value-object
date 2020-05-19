<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type;

final class TypeExpectation implements TypeExpectationInterface
{
    private ?string $acceptsClass;
    private bool $acceptsString;
    private bool $acceptsInt;
    private bool $acceptsFloat;
    private bool $acceptsBool;
    private bool $acceptsArray;


    public function __construct(
        ?string $acceptsClass,
        bool $acceptsString,
        bool $acceptsInt,
        bool $acceptsFloat,
        bool $acceptsBool,
        bool $acceptsArray
    ) {
        $acceptsAny = $acceptsClass
            || $acceptsString
            || $acceptsInt
            || $acceptsFloat
            || $acceptsBool
            || $acceptsArray;

        if (!$acceptsAny) {
            throw new \InvalidArgumentException('Cannot expect nothing.');
        }

        $this->acceptsClass = $acceptsClass;
        $this->acceptsString = $acceptsString;
        $this->acceptsInt = $acceptsInt;
        $this->acceptsFloat = $acceptsFloat;
        $this->acceptsBool = $acceptsBool;
        $this->acceptsArray = $acceptsArray;
    }

    public function acceptsNull(): bool
    {
        return false;
    }

    public function acceptsClass(string $class): bool
    {
        if ($this->acceptsClass === null) {
            return false;
        }
        return \is_a($class, $this->acceptsClass);
    }

    public function acceptsString(): bool
    {
        return $this->acceptsString;
    }

    public function acceptsInt(): bool
    {
        return $this->acceptsInt;
    }

    public function acceptsFloat(): bool
    {
        return $this->acceptsFloat;
    }

    public function acceptsBool(): bool
    {
        return $this->acceptsBool;
    }

    public function acceptsArray(): bool
    {
        return $this->acceptsArray;
    }
}

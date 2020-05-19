<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type;

interface TypeExpectationInterface
{
    /**
     * @return bool
     */
    public function acceptsNull(): bool;

    /**
     * @psalm-param class-string $class
     * @param string $class
     * @return bool
     */
    public function acceptsClass(string $class): bool;

    /**
     * @return bool
     */
    public function acceptsString(): bool;

    /**
     * @return bool
     */
    public function acceptsInt(): bool;

    /**
     * @return bool
     */
    public function acceptsFloat(): bool;

    /**
     * @return bool
     */
    public function acceptsBool(): bool;

    /**
     * @return bool
     */
    public function acceptsArray(): bool;
}

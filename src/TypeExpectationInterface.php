<?php declare(strict_types=1);

namespace Slepic\ValueObject;

interface TypeExpectationInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return bool
     */
    public function isString(): bool;

    /**
     * @return bool
     */
    public function isInt(): bool;

    /**
     * @return bool
     */
    public function isFloat(): bool;

    /**
     * @return bool
     */
    public function isArray(): bool;

    /**
     * @return bool
     */
    public function isObject(): bool;

    /**
     * @psalm-param class-string $class
     * @param string $class
     * @return bool
     */
    public function is(string $class): bool;
}

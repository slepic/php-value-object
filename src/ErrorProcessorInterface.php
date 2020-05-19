<?php declare(strict_types=1);

namespace Slepic\ValueObject;

interface ErrorProcessorInterface
{
    /**
     * @param ErrorInterface $error
     * @param array<string> $path
     * @return bool
     */
    public function process(ErrorInterface $error, array $path): bool;
}

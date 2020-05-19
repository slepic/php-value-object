<?php declare(strict_types=1);

namespace Slepic\ValueObject;

use Slepic\ValueObject\Collections\CollectionViolation;

class ErrorHandler
{
    public function handle(ErrorInterface $error, ErrorProcessorInterface $processor): void
    {
        $this->handleRecursive($error, $processor, []);
    }

    private function handleRecursive(ErrorInterface $error, ErrorProcessorInterface $processor, array $path): bool
    {
        if (!$processor->process($error, $path)) {
            return false;
        }
        foreach ($error->getViolations() as $violation) {
            if ($violation instanceof CollectionViolation) {
                $key = $violation->getKey();
                $collectionError = $violation->getError();
                if (!$this->handleRecursive($collectionError, $processor, \array_merge($path, [$key]))) {
                    return false;
                }
            }
        }
        return true;
    }
}

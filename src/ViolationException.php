<?php declare(strict_types=1);

namespace Slepic\ValueObject;

final class ViolationException extends \Exception implements ViolationExceptionInterface
{
    private array $violations;

    public function __construct(array $violations, string $message = "", int $code = 0, \Throwable $previous = null)
    {
        $this->violations = $violations;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array<ViolationInterface>
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}

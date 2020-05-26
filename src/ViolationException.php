<?php declare(strict_types=1);

namespace Slepic\ValueObject;

final class ViolationException extends \Exception implements ViolationExceptionInterface
{
    /**
     * @var array<ViolationInterface>
     */
    private array $violations;

    /**
     * @param array<ViolationInterface> $violations
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(array $violations, string $message = "", int $code = 0, \Throwable $previous = null)
    {
        $violation = \reset($violations);
        if (!$violation instanceof ViolationInterface) {
            throw new \InvalidArgumentException('Expected nonempty array of ViolationInterface instances.');
        }
        $this->violations = $violations;
        parent::__construct($message ?: $violation->getMessage(), $code, $previous);
    }

    public static function for(ViolationInterface ...$violations): self
    {
        return new self($violations);
    }

    /**
     * @return array<ViolationInterface>
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}

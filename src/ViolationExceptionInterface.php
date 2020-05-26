<?php declare(strict_types=1);

namespace Slepic\ValueObject;

/**
 * Violation exception carries an array of violations that occurred during an attempt to create a value object.
 *
 * This is the channel where value objects pass integrity violations details through a common API..
 */
interface ViolationExceptionInterface extends \Throwable
{
    /**
     * Get the list of violations.
     *
     * At least one violation must be present.
     *
     * @return array<ViolationInterface>
     */
    public function getViolations(): array;
}

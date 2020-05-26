<?php declare(strict_types=1);

namespace Slepic\ValueObject;

/**
 * All value object integrity violations are represented by this interface
 *
 * Every violation is composed of an error code (for machines) and a message (for humans).
 *
 * The error code is represented by the violation class name, its parents, and the interfaces it implements.
 * This allows for error code inheritance (ie. string length violation is still a string violation).
 * And also avoids collisions between error codes of different vendors.
 * Violations can and should also provide structured description of the restriction that was violated,
 * allowing for better customization of messages (ie. max string length violation may carry the max length allowed)
 * and more...
 */
interface ViolationInterface
{
    /**
     * Get default message of the concrete violation.
     *
     * The message should be a human readable sentence in proper english.
     * It may be used as response in simple APIs
     * or just ignore this message and generate your own based on error code.
     *
     * @return string
     */
    //public function getMessage(): string;
}

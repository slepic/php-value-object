<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Violation;

final class MissingValue extends Violation
{
    public function __construct(string $message = '')
    {
        parent::__construct($message ?: 'Missing required value.');
    }
}

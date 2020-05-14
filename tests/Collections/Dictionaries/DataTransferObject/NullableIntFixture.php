<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Collections\Dictionaries\DataTransferObject;

use Slepic\ValueObject\Collections\Dictionaries\DataTransferObject;

class NullableIntFixture extends DataTransferObject
{
    public ?int $xyz;
}

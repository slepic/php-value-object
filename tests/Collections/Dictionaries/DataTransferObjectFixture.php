<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Collections\Dictionaries;

use Slepic\ValueObject\Collections\Dictionaries\DataTransferObject;

class DataTransferObjectFixture extends DataTransferObject
{
    public int $requiredInt;
    public string $requiredString;
    public ?int $nullableInt = null;
    public ?string $nullableString = null;
}

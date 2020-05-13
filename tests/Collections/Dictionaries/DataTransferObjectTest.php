<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Collections\Dictionaries;

use PHPUnit\Framework\TestCase;

class DataTransferObjectTest extends TestCase
{
    public function testConstructor(): void
    {
        $dto = new DataTransferObjectFixture([
            'requiredInt' => 1,
            'requiredString' => 'test',
        ]);

        self::assertSame(1, $dto->requiredInt);
        self::assertSame('test', $dto->requiredString);
        self::assertSame(null, $dto->nullableInt);
        self::assertSame(null, $dto->nullableString);
    }
}

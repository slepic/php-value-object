<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\DateTime;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\DateTime\DateTimeValue;

final class DateTimeValueTest extends TestCase
{
    public function testFromString(): void
    {
        $value = DateTimeValue::fromString('2020-05-26 13:14:15');
        self::assertSame('2020-05-26T13:14:15+00:00', (string) $value);
        self::assertSame('UTC', $value->getTimezone()->getName());
    }

    public function testFromImmutable(): void
    {
        $immutable = new \DateTimeImmutable('2020-05-26 13:14:15', new \DateTimeZone('Europe/Prague'));
        $value = DateTimeValue::fromDateTimeImmutable($immutable);
        self::assertSame($immutable->getTimestamp(), $value->getTimestamp());
        self::assertSame('UTC', $value->getTimezone()->getName());
    }

    public function testFromMutable(): void
    {
        $mutable = new \DateTime('2020-05-26 13:14:15', new \DateTimeZone('Europe/Prague'));
        $value = DateTimeValue::fromDateTime($mutable);
        self::assertSame($mutable->getTimestamp(), $value->getTimestamp());
        self::assertSame('UTC', $value->getTimezone()->getName());
    }

    public function testFromFormat(): void
    {
        $value = DateTimeValue::fromFormat('d.m.Y H:i:s', '26.5.2020 13:14:15');
        self::assertSame('2020-05-26T13:14:15+00:00', (string) $value);
        self::assertSame('UTC', $value->getTimezone()->getName());
    }

    public function testJsonSerialization(): void
    {
        $value = DateTimeValue::fromString('2020-05-26 13:14:15');
        self::assertSame('"2020-05-26T13:14:15+00:00"', \json_encode($value));
    }
}

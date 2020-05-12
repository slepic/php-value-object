<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject;

final class TypeHintedMethodsFixture
{
    public int $int;
    public string $string;
    public array $array;
    public object $object;
    public \DateTimeImmutable $dateTimeImmutable;

    public static int $intStatic;
    public static string $stringStatic;
    public static array $arrayStatic;
    public static object $objectStatic;
    public static \DateTimeImmutable $dateTimeImmutableStatic;
    
    
    public function int(int $value): void
    {
    }

    public function string(string $value): void
    {
    }

    public function array(array $value): void
    {
    }

    public function object(object $value): void
    {
    }

    public function dateTimeImmutable(\DateTimeImmutable $value): void
    {
    }

    public static function intStatic(int $value): void
    {
    }

    public static function stringStatic(string $value): void
    {
    }

    public static function arrayStatic(array $value): void
    {
    }

    public static function objectStatic(object $value): void
    {
    }

    public static function dateTimeImmutableStatic(\DateTimeImmutable $value): void
    {
    }
}

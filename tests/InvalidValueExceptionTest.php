<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\InvalidValueException;
use Slepic\ValueObject\InvalidValueExceptionInterface;

final class InvalidValueExceptionTest extends TestCase
{

    /**
     * @param $value
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param array $messageSubstrings
     *
     * @dataProvider provideConstructorTestData
     */
    public function testConstructor(
        $value,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
        array $messageSubstrings = []
    ): void {
        $e = new InvalidValueException($value, $message, $code, $previous);

        self::assertInstanceOf(InvalidValueExceptionInterface::class, $e);
        InvalidValueExceptionAssertion::assert(
            $e,
            $value,
            $message,
            $code,
            $previous,
            $messageSubstrings
        );
    }

    public function provideConstructorTestData(): array
    {
        return [
            [1],
            [''],
            [10.0],
            ['hello', 'custom message'],
            ['abscdef0123', '', 0, new \Exception('test'), ['"abscdef0123" not expected']],
            [
                'abscdef0123',
                '',
                15,
                new \Exception('test'),
                ['"abscdef0123" not expected']
            ],
        ];
    }
}

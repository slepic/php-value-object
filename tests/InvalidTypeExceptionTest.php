<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject;

use PHPUnit\Framework\TestCase;
use PHPUnit\Util\Type;
use Slepic\ValueObject\InvalidTypeException;
use Slepic\ValueObject\InvalidTypeExceptionInterface;
use Slepic\ValueObject\TypeExpectation;
use Slepic\ValueObject\TypeExpectationInterface;

final class InvalidTypeExceptionTest extends TestCase
{
    /**
     * @param TypeExpectationInterface $expectation
     * @param $value
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param array $messageSubstrings
     *
     * @dataProvider provideConstructorTestData
     */
    public function testConstructor(
        TypeExpectationInterface $expectation,
        $value,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
        array $messageSubstrings = []
    ): void {
        $e = new InvalidTypeException($expectation, $value, $message, $code, $previous);

        self::assertInstanceOf(InvalidTypeExceptionInterface::class, $e);
        InvalidValueExceptionAssertion::assert(
            $e,
            $value,
            $message,
            $code,
            $previous,
            $messageSubstrings
        );
        self::assertSame($expectation, $e->getExpectation());
    }

    public function provideConstructorTestData(): array
    {
        return [
            [new TypeExpectation('int'), 1],
            [new TypeExpectation('string'), ''],
            [new TypeExpectation('float'), 10.0],
            [new TypeExpectation('string'), 'hello', 'custom message'],
            [
                new TypeExpectation('string'),
                'abscdef0123',
                '',
                0,
                new \Exception('test'),
                ['"string" not expected']
            ],
            [
                new TypeExpectation('string'),
                'abscdef0123',
                '',
                15,
                new \Exception('test'),
                ['"string" not expected']
            ],
        ];
    }
}

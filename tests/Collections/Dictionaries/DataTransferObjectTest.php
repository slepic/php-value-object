<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Collections\Dictionaries;

use PHPUnit\Framework\TestCase;
use Slepic\Tests\ValueObject\Collections\Dictionaries\DataTransferObject\NullableIntDefault10Fixture;
use Slepic\Tests\ValueObject\Collections\Dictionaries\DataTransferObject\NullableIntDefaultNullFixture;
use Slepic\Tests\ValueObject\Collections\Dictionaries\DataTransferObject\NullableIntFixture;
use Slepic\Tests\ValueObject\Collections\Dictionaries\DataTransferObject\RequiredIntFixture;
use Slepic\ValueObject\Collections\CollectionExceptionInterface;
use Slepic\ValueObject\Collections\Dictionaries\DataTransferObject;
use Slepic\ValueObject\InvalidValueExceptionInterface;

class DataTransferObjectTest extends TestCase
{
    public function testIsAbstract(): void
    {
        $reflection = new \ReflectionClass(DataTransferObject::class);
        self::assertTrue($reflection->isAbstract());
    }

    /**
     * @param array<string, mixed> $input
     * @param callable $factory
     * @param callable|null $then
     * @param array<string, callable> $errorAssertions
     *
     * @dataProvider provideConstructorData
     */
    public function testConstructor(
        array $input,
        callable $factory,
        ?callable $then = null,
        array $errorAssertions = []
    ): void {

        if ($then !== null && $errorAssertions) {
            throw new \LogicException('Cannot expect both success and failure.');
        }

        try {
            /** @var DataTransferObject $dto */
            $dto = $factory($input);
        } catch (CollectionExceptionInterface $e) {
            if ($errorAssertions) {
                self::assertSame($input, $e->getValue());
                $errors = $e->getErrors();
                self::assertCount(\count($errorAssertions), $errors);
                foreach ($errorAssertions as $key => $errorAssertion) {
                    self::assertArrayHasKey($key, $errors);
                    $errorAssertion($errors[$key]);
                }
                return;
            }
            throw $e;
        }

        if ($errorAssertions) {
            self::assertTrue(false, 'CollectionExceptionInterface not thrown.');
            return;
        }

        if ($then !== null) {
            $then($dto, $input);
        }
    }

    /**
     * @param DataTransferObject $dto
     * @param array $input
     * @return int|null Returns dummy value so can be used in arrow functions (void cannot).
     */
    private static function assertInputIsCopied(DataTransferObject $dto, array $input): ?int
    {
        foreach ($input as $key => $value) {
            self::assertSame($value, $dto->$key);
        }
        return null;
    }

    public function provideConstructorData(): array
    {
        return [

            /**
             * RequiredIntFixture
             * public int $xyz;
             */

            [
                [],
                fn(array $value) => new RequiredIntFixture($value),
                null,
                [
                    'xyz' => fn (InvalidValueExceptionInterface $e) => null,
                ]
            ],
            [
                ['xyz' => 10],
                fn(array $value) => new RequiredIntFixture($value),
                fn(RequiredIntFixture $dto, array $input) => self::assertInputIsCopied($dto, $input)
            ],
            [
                ['xyz' => 'string value'],
                fn(array $value) => new RequiredIntFixture($value),
                null,
                [
                    'xyz' => fn (InvalidValueExceptionInterface $e) => null,
                ]
            ],
                [
                ['xyz' => 10.0],
                fn($value) => new RequiredIntFixture($value),
                null,
                [
                    'xyz' => fn (InvalidValueExceptionInterface $e) => null,
                ]
            ],
                [
                ['xyz' => []],
                fn($value) => new RequiredIntFixture($value),
                null,
                [
                    'xyz' => fn (InvalidValueExceptionInterface $e) => null,
                ]
            ],
                [
                ['xyz' => (object) []],
                fn($value) => new RequiredIntFixture($value),
                null,
                [
                    'xyz' => fn (InvalidValueExceptionInterface $e) => null,
                ]
            ],

            /**
             * NullableIntFixture
             * public ?int $xyz;
             */

                [
                ['xyz' => 10],
                fn($value) => new NullableIntFixture($value),
                fn(NullableIntFixture $dto, array $input) => self::assertInputIsCopied($dto, $input)
            ],
                [
                ['xyz' => null],
                fn($value) => new NullableIntFixture($value),
                fn(NullableIntFixture $dto, array $input) => self::assertInputIsCopied($dto, $input)
            ],
                [
                ['xyz' => 'string value'],
                fn($value) => new NullableIntFixture($value),
                null,
                [
                    'xyz' => fn (InvalidValueExceptionInterface $e) => null,
                ]
            ],
                [
                ['xyz' => []],
                fn($value) => new NullableIntFixture($value),
                null,
                [
                    'xyz' => fn (InvalidValueExceptionInterface $e) => null,
                ]
            ],
                [
                ['xyz' => (object) []],
                fn($value) => new NullableIntFixture($value),
                null,
                [
                    'xyz' => fn (InvalidValueExceptionInterface $e) => null,
                ]
            ],

            /**
             * NullableIntDefaultNullFixture
             * public ?int $xyz = null;
             */

                [
                ['xyz' => 10],
                fn($value) => new NullableIntDefaultNullFixture($value),
                fn(NullableIntDefaultNullFixture $dto, array $input) => self::assertInputIsCopied($dto, $input)
            ],
                [
                ['xyz' => null],
                fn($value) => new NullableIntDefaultNullFixture($value),
                fn(NullableIntDefaultNullFixture $dto, array $input) => self::assertInputIsCopied($dto, $input)
            ],
                [
                [],
                fn($value) => new NullableIntDefaultNullFixture($value),
                function (NullableIntDefaultNullFixture $dto) {
                    self::assertSame(null, $dto->xyz);
                }
            ],

            /**
             * NullableIntDefault10Fixture
             * public ?int $xyz = 10;
             */

            [
                ['xyz' => 100],
                fn($value) => new NullableIntDefault10Fixture($value),
                fn(NullableIntDefault10Fixture $dto, array $input) => self::assertInputIsCopied($dto, $input)
            ],
            [
                [],
                fn($value) => new NullableIntDefault10Fixture($value),
                function (NullableIntDefault10Fixture $dto) {
                    self::assertSame(10, $dto->xyz);
                }
            ],
        ];
    }
}

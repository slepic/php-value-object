<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Collections\Dictionaries;

use PHPUnit\Framework\TestCase;
use Slepic\Tests\ValueObject\Collections\Dictionaries\DataTransferObject\NullableIntDefault10Fixture;
use Slepic\Tests\ValueObject\Collections\Dictionaries\DataTransferObject\NullableIntDefaultNullFixture;
use Slepic\Tests\ValueObject\Collections\Dictionaries\DataTransferObject\NullableIntFixture;
use Slepic\Tests\ValueObject\Collections\Dictionaries\DataTransferObject\RequiredIntFixture;
use Slepic\ValueObject\Collections\CollectionViolation;
use Slepic\ValueObject\Collections\Dictionaries\DataTransferObject;
use Slepic\ValueObject\ErrorInterface;
use Slepic\ValueObject\ViolationExceptionInterface;

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
        string $name,
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
        } catch (ViolationExceptionInterface $e) {
            if ($errorAssertions) {
                $violations = $e->getViolations();
                self::assertCount(\count($errorAssertions), $violations);
                $collectionErrors = [];
                foreach ($violations as $violation) {
                    if ($violation instanceof CollectionViolation) {
                        $collectionErrors[$violation->getKey()] = $violation->getError();
                    }
                }
                self::assertCount(\count($errorAssertions), $collectionErrors);
                foreach ($errorAssertions as $key => $errorAssertion) {
                    self::assertArrayHasKey($key, $collectionErrors);
                    $errorAssertion($collectionErrors[$key]);
                }
                return;
            }
            print_r($e->getViolations());
            throw $e;
        }

        if ($errorAssertions) {
            self::assertTrue(false, ViolationExceptionInterface::class . ' not thrown.');
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
                'none => required int => error',
                [],
                fn(array $value) => new RequiredIntFixture($value),
                null,
                [
                    'xyz' => fn (ErrorInterface $e) => null,
                ]
            ],
            [
                '10 => required int => ok',
                ['xyz' => 10],
                fn(array $value) => new RequiredIntFixture($value),
                fn(RequiredIntFixture $dto, array $input) => self::assertInputIsCopied($dto, $input)
            ],
            [
                'string => required int => error',
                ['xyz' => 'string value'],
                fn(array $value) => new RequiredIntFixture($value),
                null,
                [
                    'xyz' => fn (ErrorInterface $e) => null,
                ]
            ],
                [
                'float => required int => error',
                ['xyz' => 10.0],
                fn($value) => new RequiredIntFixture($value),
                null,
                [
                    'xyz' => fn (ErrorInterface $e) => null,
                ]
            ],
                [
                'array => required int => error',
                ['xyz' => []],
                fn($value) => new RequiredIntFixture($value),
                null,
                [
                    'xyz' => fn (ErrorInterface $e) => null,
                ]
            ],
                [
                'object => required int => error',
                ['xyz' => (object) []],
                fn($value) => new RequiredIntFixture($value),
                null,
                [
                    'xyz' => fn (ErrorInterface $e) => null,
                ]
            ],

            /**
             * NullableIntFixture
             * public ?int $xyz;
             */

                [
                'int => ?int => ok',
                ['xyz' => 10],
                fn($value) => new NullableIntFixture($value),
                fn(NullableIntFixture $dto, array $input) => self::assertInputIsCopied($dto, $input)
            ],
                [
                'null => ?int => ok',
                ['xyz' => null],
                fn($value) => new NullableIntFixture($value),
                fn(NullableIntFixture $dto, array $input) => self::assertInputIsCopied($dto, $input)
            ],
                [
                'string => ?int => fail',
                ['xyz' => 'string value'],
                fn($value) => new NullableIntFixture($value),
                null,
                [
                    'xyz' => fn (ErrorInterface $e) => null,
                ]
            ],
                [
                'array => ?int => error',
                ['xyz' => []],
                fn($value) => new NullableIntFixture($value),
                null,
                [
                    'xyz' => fn (ErrorInterface $e) => null,
                ]
            ],
                [
                'object => ?int => error',
                ['xyz' => (object) []],
                fn($value) => new NullableIntFixture($value),
                null,
                [
                    'xyz' => fn (ErrorInterface $e) => null,
                ]
            ],

            /**
             * NullableIntDefaultNullFixture
             * public ?int $xyz = null;
             */

                [
                'int => ?int=null => ok',
                ['xyz' => 10],
                fn($value) => new NullableIntDefaultNullFixture($value),
                fn(NullableIntDefaultNullFixture $dto, array $input) => self::assertInputIsCopied($dto, $input)
            ],
                [
                'null => ?int=null => ok',
                ['xyz' => null],
                fn($value) => new NullableIntDefaultNullFixture($value),
                fn(NullableIntDefaultNullFixture $dto, array $input) => self::assertInputIsCopied($dto, $input)
            ],
                [
                'none => ?int=null => ok',
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
                'int => ?int=10 => ok',
                ['xyz' => 100],
                fn($value) => new NullableIntDefault10Fixture($value),
                fn(NullableIntDefault10Fixture $dto, array $input) => self::assertInputIsCopied($dto, $input)
            ],
            [
                'none => ?int=10 => 10',
                [],
                fn($value) => new NullableIntDefault10Fixture($value),
                function (NullableIntDefault10Fixture $dto) {
                    self::assertSame(10, $dto->xyz);
                }
            ],
        ];
    }
}

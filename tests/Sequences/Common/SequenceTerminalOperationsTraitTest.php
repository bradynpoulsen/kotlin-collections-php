<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Common;

use BradynPoulsen\Kotlin\Collections\MapEntry;
use BradynPoulsen\Kotlin\InvalidArgumentException;
use BradynPoulsen\Kotlin\NoSuchElementException;
use BradynPoulsen\Kotlin\Pair;
use BradynPoulsen\Kotlin\Types\Types;
use BradynPoulsen\Kotlin\UnsupportedOperationException;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Sequences\emptySequence;
use function BradynPoulsen\Kotlin\Sequences\sequenceOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceTerminalOperationsTrait
 * @covers \BradynPoulsen\Kotlin\Pair
 * @covers ::\BradynPoulsen\Kotlin\pair
 */
class SequenceTerminalOperationsTraitTest extends TestCase
{
    /**
     * @test
     */
    public function all_basicUsage(): void
    {
        $sequence = sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6]);

        self::assertTrue(
            $sequence->all(
                function (int $n): bool {
                    return $n < 10;
                }
            )
        );
        self::assertFalse(
            $sequence->all(
                function (int $n): bool {
                    return $n % 2 == 0;
                }
            )
        );
        self::assertFalse(
            $sequence->all(
                function (int $n): bool {
                    return $n > 10;
                }
            )
        );
    }

    /**
     * @test
     */
    public function any_basicUsage(): void
    {
        $sequence = sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6]);

        self::assertTrue(
            $sequence->any(
                function (int $n): bool {
                    return $n < 10;
                }
            )
        );
        self::assertTrue(
            $sequence->any(
                function (int $n): bool {
                    return $n % 2 == 0;
                }
            )
        );
        self::assertFalse(
            $sequence->any(
                function (int $n): bool {
                    return $n > 10;
                }
            )
        );
    }

    /**
     * @test
     */
    public function none_basicUsage(): void
    {
        $sequence = sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6]);

        self::assertFalse(
            $sequence->none(
                function (int $n): bool {
                    return $n < 10;
                }
            )
        );
        self::assertFalse(
            $sequence->none(
                function (int $n): bool {
                    return $n % 2 == 0;
                }
            )
        );
        self::assertTrue(
            $sequence->none(
                function (int $n): bool {
                    return $n > 10;
                }
            )
        );
    }

    /**
     * @test
     */
    public function associate_basicUsage(): void
    {
        self::assertSame(
            [
                [1, '1'],
                [2, '4'],
                [3, '9'],
                [4, '16'],
                [5, '25'],
                [6, '36']
            ],
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->associate(
                    Types::integer(),
                    Types::string(),
                    function (int $value): Pair {
                        return new Pair($value, (string)($value * $value));
                    }
                )
                ->asSequence()
                ->map(
                    Types::arrayOf(),
                    function (MapEntry $entry): array {
                        return [$entry->getKey(), $entry->getValue()];
                    }
                )
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function associateBy_basicUsage(): void
    {
        self::assertSame(
            [
                ['1', 1],
                ['4', 2],
                ['9', 3],
                ['16', 4],
                ['25', 5],
                ['36', 6]
            ],
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->associateBy(
                    Types::string(),
                    function (int $value): string {
                        return (string)($value * $value);
                    }
                )
                ->asSequence()
                ->map(
                    Types::arrayOf(),
                    function (MapEntry $entry): array {
                        return [$entry->getKey(), $entry->getValue()];
                    }
                )
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function associateWith_basicUsage(): void
    {
        self::assertSame(
            [
                [1, '1'],
                [2, '4'],
                [3, '9'],
                [4, '16'],
                [5, '25'],
                [6, '36']
            ],
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->associateWith(
                    Types::string(),
                    function (int $value): string {
                        return (string)($value * $value);
                    }
                )
                ->asSequence()
                ->map(
                    Types::arrayOf(),
                    function (MapEntry $entry): array {
                        return [$entry->getKey(), $entry->getValue()];
                    }
                )
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function average_basicUsage(): void
    {
        self::assertSame(
            3.5,
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->average()
        );
    }

    /**
     * @test
     */
    public function averageBy_basicUsage(): void
    {
        self::assertSame(
            7.0,
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->map(
                    Types::object(),
                    function (int $value): object {
                        return $this->createWrapper($value);
                    }
                )
                ->averageBy(
                    function (object $wrapper): int {
                        return $wrapper->value * 2;
                    }
                )
        );
    }

    /**
     * @test
     */
    public function count_basicUsage(): void
    {
        self::assertSame(
            6,
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->count()
        );
    }

    /**
     * @test
     */
    public function countBy_basicUsage(): void
    {
        self::assertSame(
            3,
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->countBy(
                    function (int $n): bool {
                        return $n % 2 === 0;
                    }
                )
        );
    }

    /**
     * @test
     */
    public function first_basicUsage(): void
    {
        self::assertSame(
            1,
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->first()
        );
    }

    /**
     * @test
     */
    public function first_emptyThrowsException(): void
    {
        $this->expectException(NoSuchElementException::class);
        emptySequence()->first();
    }

    /**
     * @test
     */
    public function firstBy_basicUsage(): void
    {
        self::assertSame(
            2,
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->firstBy(
                function (int $n): bool {
                    return $n % 2 === 0;
                }
            )
        );
    }

    /**
     * @test
     */
    public function firstBy_emptyThrowsException(): void
    {
        $this->expectException(NoSuchElementException::class);
        emptySequence()->firstBy(
            function (int $n): bool {
                return $n % 2 === 0;
            }
        );
    }

    /**
     * @test
     */
    public function firstByOrNull_basicUsage(): void
    {
        self::assertSame(
            2,
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->firstByOrNull(
                function (int $n): bool {
                    return $n % 2 === 0;
                }
            )
        );
    }

    /**
     * @test
     */
    public function firstByOrNull_emptyReturnsNull(): void
    {
        self::assertNull(
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->firstByOrNull(
                function (int $n): bool {
                    return $n > 10;
                }
            )
        );
    }

    /**
     * @test
     */
    public function firstOrNull_basicUsage(): void
    {
        self::assertSame(
            1,
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->firstOrNull()
        );
    }

    /**
     * @test
     */
    public function firstOrNull_emptyReturnsNull(): void
    {
        self::assertNull(emptySequence()->firstOrNull());
    }

    /**
     * @test
     */
    public function fold_basicUsage(): void
    {
        self::assertSame(
            720,
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->fold(
                    1,
                    function (int $acc, int $n): int {
                        return $acc * $n;
                    }
                )
        );
    }

    /**
     * @test
     */
    public function foldIndexed_basicUsage(): void
    {
        self::assertSame(
            8,
            sequenceOf(Types::integer(), [1, 2, 3])
                ->foldIndexed(
                    0,
                    function (int $acc, int $index, int $value): int {
                        return $acc + ($index * $value);
                    }
                )
        );
    }

    /**
     * @test
     */
    public function isEmpty_basicUsage(): void
    {
        $sequence = sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6]);
        self::assertFalse($sequence->isEmpty());
        self::assertTrue($sequence->isNotEmpty());

        $limited = $sequence->take(0);
        self::assertTrue($limited->isEmpty());
        self::assertFalse($limited->isNotEmpty());
    }

    /**
     * @test
     */
    public function joinToString_basicUsage(): void
    {
        self::assertSame(
            '1, 2, 3, 4, 5, 6',
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->joinToString()
        );

        self::assertSame(
            '["1", "2", "3", "4", "5", "6"]',
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->joinToString(
                    $separator = ', ',
                    $prefix = '[',
                    $postfix = ']',
                    $limit = -1,
                    $truncated = '...',
                    $transform = function (int $n): string {
                        return "\"$n\"";
                    }
                )
        );

        self::assertSame(
            'it goes: 1 then 2 then 3 then ...',
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->joinToString(
                    $separator = ' then ',
                    $prefix = 'it goes: ',
                    $postfix = '',
                    $limit = 3
                )
        );
    }

    /**
     * @test
     */
    public function last_basicUsage(): void
    {
        self::assertSame(
            6,
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->last()
        );
    }

    /**
     * @test
     */
    public function last_emptyThrowsException(): void
    {
        $this->expectException(NoSuchElementException::class);
        emptySequence()->last();
    }

    /**
     * @test
     */
    public function lastBy_basicUsage(): void
    {
        self::assertSame(
            6,
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->lastBy(
                function (int $n): bool {
                    return $n % 2 === 0;
                }
            )
        );
    }

    /**
     * @test
     */
    public function lastBy_emptyThrowsException(): void
    {
        $this->expectException(NoSuchElementException::class);
        emptySequence()->lastBy(
            function (int $n): bool {
                return $n % 2 === 0;
            }
        );
    }

    /**
     * @test
     */
    public function lastByOrNull_basicUsage(): void
    {
        self::assertSame(
            6,
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->lastByOrNull(
                function (int $n): bool {
                    return $n % 2 === 0;
                }
            )
        );
    }

    /**
     * @test
     */
    public function lastByOrNull_emptyReturnsNull(): void
    {
        self::assertNull(
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->lastByOrNull(
                function (int $n): bool {
                    return $n > 10;
                }
            )
        );
    }

    /**
     * @test
     */
    public function lastOrNull_basicUsage(): void
    {
        self::assertSame(
            6,
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->lastOrNull()
        );
    }

    /**
     * @test
     */
    public function lastOrNull_emptyReturnsNull(): void
    {
        self::assertNull(emptySequence()->lastOrNull());
    }

    /**
     * @test
     */
    public function max_basicUsage(): void
    {
        self::assertSame(6, sequenceOf(Types::integer(), [1, 6, 2, 5, 3, 4])->max());
        self::assertSame(6.0, sequenceOf(Types::float(), [1.0, 6.0, 2.0, 5.0, 3.0, 4.0])->max());
    }

    /**
     * @test
     */
    public function max_emptyReturnsNull(): void
    {
        self::assertNull(emptySequence()->max());
    }

    /**
     * @test
     */
    public function maxBy_basicUsage(): void
    {
        self::assertSame(
            6,
            sequenceOf(Types::integer(), [1, 6, 2, 5, 3, 4])
                ->map(
                    Types::object(),
                    function (int $n): object {
                        return $this->createWrapper($n);
                    }
                )
                ->maxBy(
                    function (object $obj): int {
                        return $obj->value;
                    }
                )
                ->value
        );
    }

    /**
     * @test
     */
    public function maxBy_emptyReturnsNull(): void
    {
        self::assertNull(
            emptySequence()->maxBy(
                function (int $n): int {
                    return $n * 2;
                }
            )
        );
    }

    /**
     * @test
     */
    public function maxWith_basicUsage(): void
    {
        self::assertSame(
            1,
            sequenceOf(Types::integer(), [6, 1, 2, 5, 3, 4])
                ->maxWith(
                    function (int $a, int $b): int {
                        return $b - $a;
                    }
                )
        );
    }

    /**
     * @test
     */
    public function maxWith_emptyReturnsNull(): void
    {
        self::assertNull(
            emptySequence()->maxWith(
                function (int $a, int $b): int {
                    return $a * $b;
                }
            )
        );
    }

    /**
     * @test
     */
    public function min_basicUsage(): void
    {
        self::assertSame(1, sequenceOf(Types::integer(), [6, 1, 2, 5, 3, 4])->min());
        self::assertSame(1.0, sequenceOf(Types::float(), [6.0, 1.0, 2.0, 5.0, 3.0, 4.0])->min());
    }

    /**
     * @test
     */
    public function min_emptyReturnsNull(): void
    {
        self::assertNull(emptySequence()->min());
    }

    /**
     * @test
     */
    public function minBy_basicUsage(): void
    {
        self::assertSame(
            1,
            sequenceOf(Types::integer(), [6, 1, 2, 5, 3, 4])
                ->map(
                    Types::object(),
                    function (int $n): object {
                        return $this->createWrapper($n);
                    }
                )
                ->minBy(
                    function (object $obj): int {
                        return $obj->value;
                    }
                )
                ->value
        );
    }

    /**
     * @test
     */
    public function minBy_emptyReturnsNull(): void
    {
        self::assertNull(
            emptySequence()->minBy(
                function (int $n): int {
                    return $n * 2;
                }
            )
        );
    }

    /**
     * @test
     */
    public function minWith_basicUsage(): void
    {
        self::assertSame(
            6,
            sequenceOf(Types::integer(), [2, 1, 6, 5, 3, 4])
                ->minWith(
                    function (int $a, int $b): int {
                        return $b - $a;
                    }
                )
        );
    }

    /**
     * @test
     */
    public function minWith_emptyReturnsNull(): void
    {
        self::assertNull(
            emptySequence()->minWith(
                function (int $a, int $b): int {
                    return $a * $b;
                }
            )
        );
    }

    /**
     * @test
     */
    public function partition_basicUsage(): void
    {
        $pairs = sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
            ->partition(
                function (int $n): bool {
                    return $n % 2 === 0;
                }
            );

        self::assertSame([2, 4, 6], $pairs->getFirst()->toArray());
        self::assertSame([1, 3, 5], $pairs->getSecond()->toArray());
    }

    /**
     * @test
     */
    public function reduce_basicUsage(): void
    {
        self::assertSame(
            720,
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->reduce(
                    function (int $acc, int $n): int {
                        return $acc * $n;
                    }
                )
        );
    }

    /**
     * @test
     */
    public function reduce_errorsWhenEmpty(): void
    {
        $this->expectException(UnsupportedOperationException::class);
        emptySequence()->reduce(
            function (): void {
            }
        );
    }

    /**
     * @test
     */
    public function reduceIndexed_basicUsage(): void
    {
        self::assertSame(
            33,
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->reduceIndexed(
                    function (int $acc, int $index, int $n): int {
                        return $acc + (($index % 2) + 1) * $n;
                    }
                )
        );
    }

    /**
     * @test
     */
    public function reduceIndexed_errorsWhenEmpty(): void
    {
        $this->expectException(UnsupportedOperationException::class);
        emptySequence()->reduceIndexed(
            function (): void {
            }
        );
    }

    /**
     * @test
     */
    public function single_basicUsage(): void
    {
        self::assertSame(
            10,
            sequenceOf(Types::integer(), [10])->single()
        );
    }

    /**
     * @test
     */
    public function single_errorsWhenEmpty(): void
    {
        $this->expectException(NoSuchElementException::class);
        emptySequence()->single();
    }

    /**
     * @test
     */
    public function single_errorsWhenNotSingle(): void
    {
        $this->expectException(InvalidArgumentException::class);
        sequenceOf(Types::integer(), [1, 2])->single();
    }

    /**
     * @test
     */
    public function singleOrNull_basicUsage(): void
    {
        self::assertSame(
            10,
            sequenceOf(Types::integer(), [10])->singleOrNull()
        );
    }

    /**
     * @test
     */
    public function singleOrNull_nullWhenEmpty(): void
    {
        self::assertNull(emptySequence()->singleOrNull());
    }

    /**
     * @test
     */
    public function singleOrNull_nullWhenNotSingle(): void
    {
        self::assertNull(sequenceOf(Types::integer(), [1, 2])->singleOrNull());
    }

    /**
     * @test
     */
    public function singleBy_basicUsage(): void
    {
        self::assertSame(
            2,
            sequenceOf(Types::integer(), [1, 2, 3])->singleBy(
                function (int $n): bool {
                    return $n % 2 === 0;
                }
            )
        );
    }

    /**
     * @test
     */
    public function singleBy_errorsWhenEmpty(): void
    {
        $this->expectException(NoSuchElementException::class);
        emptySequence()->singleBy(
            function (int $n): bool {
                return $n % 2 === 0;
            }
        );
    }

    /**
     * @test
     */
    public function singleBy_errorsWhenNotSingle(): void
    {
        $this->expectException(InvalidArgumentException::class);
        sequenceOf(Types::integer(), [2, 3, 4])->singleBy(
            function (int $n): bool {
                return $n % 2 === 0;
            }
        );
    }

    /**
     * @test
     */
    public function singleByOrNull_basicUsage(): void
    {
        self::assertSame(
            2,
            sequenceOf(Types::integer(), [1, 2, 3])->singleByOrNull(
                function (int $n): bool {
                    return $n % 2 === 0;
                }
            )
        );
    }

    /**
     * @test
     */
    public function singleByOrNull_nullWhenEmpty(): void
    {
        self::assertNull(
            emptySequence()->singleByOrNull(
                function (int $n): bool {
                    return $n % 2 === 0;
                }
            )
        );
    }

    /**
     * @test
     */
    public function singleByOrNull_nullWhenNotSingle(): void
    {
        self::assertNull(
            sequenceOf(Types::integer(), [2, 3, 4])->singleByOrNull(
                function (int $n): bool {
                    return $n % 2 === 0;
                }
            )
        );
    }

    /**
     * @test
     */
    public function sum_basicUsage(): void
    {
        self::assertSame(
            21,
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->sum()
        );
    }

    /**
     * @test
     */
    public function sumBy_basicUsage(): void
    {
        self::assertSame(
            21,
            sequenceOf(Types::object(), [
                $this->createWrapper(1),
                $this->createWrapper(2),
                $this->createWrapper(3),
                $this->createWrapper(4),
                $this->createWrapper(5),
                $this->createWrapper(6)
            ])->sumBy(function (object $obj): int {
                return $obj->value;
            })
        );
    }

    /**
     * @test
     */
    public function sumFloat_basicUsage(): void
    {
        self::assertSame(
            21.0,
            sequenceOf(Types::float(), [1.0, 2.0, 3.0, 4.0, 5.0, 6.0])->sumFloat()
        );
    }

    /**
     * @test
     */
    public function sumByFloat_basicUsage(): void
    {
        self::assertSame(
            21.0,
            sequenceOf(Types::object(), [
                $this->createWrapper(1.0),
                $this->createWrapper(2.0),
                $this->createWrapper(3.0),
                $this->createWrapper(4.0),
                $this->createWrapper(5.0),
                $this->createWrapper(6.0)
            ])->sumByFloat(function (object $obj): float {
                return $obj->value;
            })
        );
    }

    /**
     * @test
     */
    public function unzip_basicUsage(): void
    {
        $first = sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6, 7, 8]);
        $second = sequenceOf(Types::string(), ["one", "two", "three", "four"]);

        $pairs = $first->zip($second)->unzip(Types::integer(), Types::string());
        self::assertEquals(
            $first->take(4)->toArray(),
            $pairs->getFirst()->toArray()
        );
        self::assertEquals(
            $second->toArray(),
            $pairs->getSecond()->toArray()
        );
    }

    private function createWrapper($value): object
    {
        return new class($value)
        {
            public $value;

            public function __construct($value)
            {
                $this->value = $value;
            }
        };
    }
}
